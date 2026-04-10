<?php

namespace App\Jobs;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\ImportBatch;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductVariant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcessProductImportBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;
    public int $tries   = 3;

    private array $rows;
    private int   $userId;
    private int   $importBatchId;
    private int   $batchNumber;

    public function __construct(array $rows, int $userId, int $importBatchId, int $batchNumber)
    {
        $this->rows          = $rows;
        $this->userId        = $userId;
        $this->importBatchId = $importBatchId;
        $this->batchNumber   = $batchNumber;
    }

    public function handle(): void
    {
        $importBatch = ImportBatch::find($this->importBatchId);
        if (!$importBatch) return;

        $processedProducts = [];
        $importedCount     = 0;
        $failedCount       = 0;
        $errors            = [];

        DB::beginTransaction();

        try {
            foreach ($this->rows as $row) {

                if (count($row) < 15) continue;

                $productName     = trim($row[2]);
                $categoryName    = trim($row[4]);
                $productTypeName = trim($row[6]);
                $description     = trim($row[7]);
                $mainImageUrl    = trim($row[8]);
                $allImagesUrl    = trim($row[9]);
                $status          = strtolower(trim($row[10])) === 'active' ? 1 : 0;
                $variantSku      = trim($row[14]);
                $variantName     = trim($row[15]);
                $variantPrice    = trim($row[16]);
                $variantStock    = trim($row[17]);
                $csvAttributeId  = trim($row[18]);
                $attributeValue  = trim($row[19]);

                if (empty($productName)) continue;

                $categoryId = null;
                if (!empty($categoryName)) {
                    $category   = Category::firstOrCreate(
                        ['name' => $categoryName],
                        ['slug' => Str::slug($categoryName), 'status' => 1]
                    );
                    $categoryId = $category->id;
                }

                if (!empty($productTypeName)) {
                    $productType = ProductType::firstOrCreate(
                        ['name' => $productTypeName],
                        ['slug' => Str::slug($productTypeName)]
                    );
                } else {
                    $productType = ProductType::firstOrCreate(
                        ['name' => 'Default'],
                        ['slug' => 'default']
                    );
                }
                $productTypeId = $productType->id;

                $savedMainImage = null;
                if (!empty($mainImageUrl) && filter_var($mainImageUrl, FILTER_VALIDATE_URL)) {
                    $savedMainImage = $this->downloadAndSaveImage($mainImageUrl, 'products');
                }

                $savedAllImages = [];
                if (!empty($allImagesUrl)) {
                    foreach (explode(' | ', $allImagesUrl) as $imgUrl) {
                        $imgUrl = trim($imgUrl);
                        if (filter_var($imgUrl, FILTER_VALIDATE_URL)) {
                            $path = $this->downloadAndSaveImage($imgUrl, 'products');
                            if ($path) $savedAllImages[] = $path;
                        }
                    }
                }

                $cacheKey = $this->userId . '_' . $productName;

                if (!isset($processedProducts[$cacheKey])) {
                    $product = Product::firstOrCreate(
                        ['user_id' => $this->userId, 'name' => $productName],
                        [
                            'category_id'     => $categoryId,
                            'product_type_id' => $productTypeId,
                            'description'     => $description,
                            'image'           => $savedMainImage,
                            'images'          => !empty($savedAllImages) ? $savedAllImages : null,
                            'status'          => $status,
                        ]
                    );
                    $processedProducts[$cacheKey] = $product->id;
                }

                $productId = $processedProducts[$cacheKey];

                if (empty($variantSku) && empty($variantName)) {
                    $importedCount++;
                    continue;
                }

                $variantExists = ProductVariant::where('product_id', $productId)
                    ->where('sku', $variantSku)
                    ->exists();

                if (!$variantExists) {
                    $attributeId      = null;
                    $attributeValueId = null;

                    if (!empty($attributeValue)) {
                        $attribute = null;
                        if (!empty($csvAttributeId) && is_numeric($csvAttributeId)) {
                            $attribute = Attribute::find((int)$csvAttributeId);
                        }

                        if ($attribute) {
                            $attrValueRow = AttributeValue::where('attribute_id', $attribute->id)
                                ->where('value', $attributeValue)
                                ->first();

                            if ($attrValueRow) {
                                $attributeId      = $attribute->id;
                                $attributeValueId = $attrValueRow->id;
                            } else {
                                $newAttrValue     = AttributeValue::create([
                                    'attribute_id' => $attribute->id,
                                    'value'        => $attributeValue,
                                ]);
                                $attributeId      = $attribute->id;
                                $attributeValueId = $newAttrValue->id;
                            }
                        } else {
                            $attrValueRow = AttributeValue::where('value', $attributeValue)->first();

                            if ($attrValueRow) {
                                $attributeId      = $attrValueRow->attribute_id;
                                $attributeValueId = $attrValueRow->id;
                            } else {
                                $newAttribute     = Attribute::firstOrCreate(['name' => 'General']);
                                $newAttrValue     = AttributeValue::create([
                                    'attribute_id' => $newAttribute->id,
                                    'value'        => $attributeValue,
                                ]);
                                $attributeId      = $newAttribute->id;
                                $attributeValueId = $newAttrValue->id;
                            }
                        }
                    }

                    $variant = ProductVariant::create([
                        'product_id'   => $productId,
                        'sku'          => $variantSku,
                        'name'         => $variantName,
                        'price'        => $variantPrice ?: 0,
                        'stock'        => $variantStock ?: 0,
                        'attribute_id' => $attributeId,
                    ]);

                    if ($attributeValueId) {
                        DB::table('attribute_value_product_variant')->insert([
                            'variant_id'         => $variant->id,
                            'attribute_value_id' => $attributeValueId,
                        ]);
                    }
                }

                $importedCount++;
            }

            DB::commit();

            DB::table('import_batches')
                ->where('id', $this->importBatchId)
                ->update([
                    'processed_batches' => DB::raw('processed_batches + 1'),
                    'imported_rows'     => DB::raw("imported_rows + $importedCount"),
                ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $failedCount = count($this->rows);

            DB::table('import_batches')
                ->where('id', $this->importBatchId)
                ->update([
                    'processed_batches' => DB::raw('processed_batches + 1'),
                    'failed_rows'       => DB::raw("failed_rows + $failedCount"),
                    'errors'            => DB::raw("CONCAT(IFNULL(errors,''), 'Batch {$this->batchNumber}: " . addslashes($e->getMessage()) . "\n')"),
                ]);
        }

        // Check karo kya saare batches process ho gaye
        $fresh = ImportBatch::find($this->importBatchId);
        if ($fresh && $fresh->processed_batches >= $fresh->total_batches) {
            $finalStatus = 'completed';
            if ($fresh->failed_rows > 0 && $fresh->imported_rows === 0) {
                $finalStatus = 'failed';
            } elseif ($fresh->failed_rows > 0) {
                $finalStatus = 'partial';
            }
            $fresh->update(['status' => $finalStatus]);
        }
    }

    private function downloadAndSaveImage(string $url, string $folder): ?string
    {
        try {
            $appUrl = rtrim(config('app.url'), '/');

            if (str_starts_with($url, $appUrl)) {
                $path = str_replace($appUrl . '/storage/', '', $url);
                if (Storage::disk('public')->exists($path)) {
                    return $path;
                }
            }

            $context = stream_context_create([
                'http' => ['timeout' => 10, 'user_agent' => 'Mozilla/5.0'],
                'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false],
            ]);

            $contents = @file_get_contents($url, false, $context);
            if ($contents === false) return null;

            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
            $extension = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp', 'gif'])
                ? strtolower($extension) : 'jpg';

            $filename = $folder . '/' . uniqid('img_', true) . '.' . $extension;
            Storage::disk('public')->put($filename, $contents);

            return $filename;

        } catch (\Exception $e) {
            return null;
        }
    }
}