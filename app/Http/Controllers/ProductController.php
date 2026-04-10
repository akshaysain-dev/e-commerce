<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use App\Models\AttributeValue;
use App\Models\Attribute;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\RecentView;
use App\Models\ProductType;
use App\Models\ImportBatch;
use App\Models\Margin;
use App\Jobs\ProcessProductImportBatch;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function show_(){
        $products = Product::with(['category', 'firstVariant'])->orderBy('created_at', 'desc')->paginate(8);
        return view('admin.products.index', compact('products'));
    }

    public function add_product(){
        $categories = Category::all();
        $attributes = Attribute::all();
        $attributeValues = AttributeValue::all();
        $types = ProductType::all();
        return view('admin.products.create', compact('categories','attributes', 'attributeValues','types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_type_id' => 'required|exists:product_types,id',
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',          
            'images.*' => 'nullable|image|max:2048', 
            'status' => 'nullable|boolean',
            'variants.*.attribute_value_id' => 'required',
        ]);

        $product = new Product();
        $product->category_id = $request->category_id;
        $product->user_id = $request->user_id;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->status = $request->has('status') ? 1 : 0;
        $product->product_type_id = $request->product_type_id;
		
		$margin = Margin::where('type_id',$product->product_type_id)->first();
        //dd($margin->name);
		$margin_percentage = $margin->percentage;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->image = $path;
        }

        $additionalImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $additionalImages[] = $img->store('products', 'public');
            }
        }

        $product->images = $additionalImages;
        $product->save();

        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                $attrValue = AttributeValue::find($variantData['attribute_value_id']);
                $variantName = $attrValue ? $attrValue->value : 'Default';

                $skuBase = Str::slug($product->name . '-' . $variantName);
                $uniqueSku = strtoupper($skuBase . '-' . Str::random(4));

                $variant = $product->variants()->create([
                    'name'  => $variantName,
                    'price' => $variantData['price'] ?? $product->price,
					'margin_price' => ($variantData['price'])*(1+($margin_percentage/100)) ?? $product->price,
                    'stock' => $variantData['stock'] ?? 0,
                    'sku'   => $uniqueSku,
                    'attribute_id' => $variantData['attribute_id'],
                ]);

                if (!empty($variantData['attribute_value_id'])) {
                    $variant->attributeValues()->attach($variantData['attribute_value_id']);
                }
            }
        }

        return redirect()->route('admin_product')->with('success', 'Product and variants added!');
    }

    public function delete($id){
        $product = Product::find($id);
        
        if ($product) {
            $product->delete();
            return back()->with('success', 'Product deleted successfully!');
        }

        return back()->with('error', 'Record not found.');
    }

    public function edit($id){
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $attributes = Attribute::all();
        $attributeValues = AttributeValue::all();
        $types = ProductType::all();
        $tags = \App\Models\Tag::orderBy('name')->get();
        session(['products_url' => url()->previous()]);

        return view('admin.products.edit', compact('product','categories','attributes','attributeValues','types','tags'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_type_id' => 'required|exists:product_types,id',
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'nullable|boolean',
            'variants.*.sku' => 'nullable|string',
            'variants.*.price' => 'nullable|numeric',
            'variants.*.attribute_value_id' => 'nullable|exists:attribute_values,id',
        ]);

        $product = Product::findOrFail($id);

        $product->category_id = $request->category_id;
        $product->user_id = $request->user_id;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->status = $request->has('status') ? 1 : 0;
        $product->product_type_id = $request->product_type_id;
        $product->tags()->sync($request->tags ?? []);
		
		$margin = Margin::where('type_id',$product->product_type_id)->first();
		if (!$margin) {
			return back()->withErrors(['msg' => 'Margin not found for this product type. '.$product->product_type_id]);
		}
		$margin_percentage = $margin->percentage;

        if ($request->hasFile('image')) {
            if ($product->image && \Storage::disk('public')->exists($product->image)) {
                \Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $currentImages = $product->images ?? [];

        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $index) {
                if (isset($currentImages[$index])) {
                    if (\Storage::disk('public')->exists($currentImages[$index])) {
                        \Storage::disk('public')->delete($currentImages[$index]);
                    }
                    unset($currentImages[$index]);
                }
            }
            $currentImages = array_values($currentImages);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $currentImages[] = $img->store('products', 'public');
            }
        }

        $product->images = $currentImages;
        $product->save();

        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                if (!empty($variantData['attribute_value_id'])) {
                    
                    $attrValue = \App\Models\AttributeValue::find($variantData['attribute_value_id']);
                    $variantName = $attrValue ? $attrValue->value : 'Variant';

                    $skuBase = Str::slug($product->name . '-' . $variantName);
                    $uniqueSku = strtoupper($skuBase . '-' . Str::random(4));
					$marginPrice = ($variantData['price']) * (1 + ($margin_percentage / 100));
                    $data = [
                        'name'  => $variantName,
                        'price' => $variantData['price'] ?? $product->price,
						'margin_price' => $marginPrice ?? $product->price,
                        'stock' => $variantData['stock'] ?? 0,
                        'attribute_id' => $variantData['attribute_id'] ??0,
                    ];

                    $existingVariant = isset($variantData['id']) 
                        ? \App\Models\ProductVariant::find($variantData['id']) 
                        : null;

                    if (!$existingVariant || empty($existingVariant->sku) ) {
                        $skuBase = \Illuminate\Support\Str::slug($product->name . '-' . $variantName);
                        $data['sku'] = $uniqueSku;
                    }

                    $variant = $product->variants()->updateOrCreate(
                        ['id' => $variantData['id'] ?? null],
                        $data
                    );

                    $variant->attributeValues()->sync([$variantData['attribute_value_id']]);
                }
            }
        }
		
		if($request->has('delete_variants')){
			foreach ($request->delete_variants as $delete_variant) {
				$variant = \App\Models\ProductVariant::find($delete_variant);
					$variant->delete();
				}
		}

        $returnUrl = session('products_url', route('admin_product'));
        return redirect($returnUrl)->with('success', 'Product and variants updated successfully!');
    }



    public function all_products(Request $request)
    {
        $query = Product::with(['category', 'firstVariant'])
            ->where('status', 1)
            ->whereHas('firstVariant', function ($q) use ($request) {

                if ($request->filled('min_price') && $request->filled('max_price')) {
                    $q->whereBetween('price', [
                        (float) $request->min_price,
                        (float) $request->max_price,
                    ]);
                } elseif ($request->filled('min_price')) {
                    $q->where('price', '>=', (float) $request->min_price);
                } elseif ($request->filled('max_price')) {
                    $q->where('price', '<=', (float) $request->max_price);
                }
            })
            ->orderBy('created_at', 'desc');

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products   = $query->paginate(8)->withQueryString();
        $categories = Category::orderBy('name', 'asc')->get();

        return view('frontend.all_product', compact('products', 'categories'));
    }

    public function suggest(Request $request) {
        $results = Product::where('name', 'like', '%'.$request->q.'%')
                        ->select('id','name','price','image')
                        ->limit(6)->get();
        return response()->json($results);
    }

    public function saveRecent(Request $request) {
        $productId = $request->product_id;
        $customerId = session('customer_id'); 

        if ($customerId && $productId) {
            \DB::table('recent_views')
                ->where('customer_id', $customerId)
                ->where('product_id', $productId)
                ->delete();

            \DB::table('recent_views')->insert([
                'customer_id' => $customerId,
                'product_id'  => $productId,
                'created_at'  => now(),
                'updated_at'  => now()
            ]);

            $idsToKeep = \DB::table('recent_views')
                ->where('customer_id', $customerId)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->pluck('id');

            \DB::table('recent_views')
                ->where('customer_id', $customerId)
                ->whereNotIn('id', $idsToKeep)
                ->delete();

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'ignored'], 200);
    }

    public function exportCsv()
    {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $filename = 'products_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $output = fopen('php://temp', 'r+');
        $baseUrl = rtrim(config('app.url'), '/') . '/storage/';

        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($output, [
            'Product ID',
            'User ID',
            'Product Name',
            'Category ID',
            'Category Name',
            'Product Type ID',
            'Product Type Name',
            'Description',
            'Main Image',
            'All Images',
            'Status',
            'Product Created At',
            'Variant ID',
            'Variant Product Id FK',
            'Variant SKU',
            'Variant Name',
            'Variant Price',
            'Variant Stock',
            'Attribute ID',
            'Attribute Value',
            'Variant Created At',
        ]);

        Product::select([
                'id', 'user_id', 'name', 'category_id',
                'product_type_id', 'description', 'image', 'images',
                'status', 'created_at'
            ])
            ->with([
                'category'  => fn($q) => $q->select(['id', 'name']),
                'type'      => fn($q) => $q->select(['id', 'name']),
                'variants'  => function ($q) {
                    $q->select([
                        'id', 'product_id', 'sku', 'name',
                        'price', 'stock', 'attribute_id', 'created_at'
                    ])->with([
                        'attribute'       => fn($a) => $a->select(['id', 'name']),
                        'attributeValues' => fn($av) => $av->select(['attribute_values.id', 'attribute_values.value']),
                    ]);
                }
            ])
            ->chunk(100, function ($products) use ($output, $baseUrl) {
                foreach ($products as $product) {

                    $imagesRaw = $product->images;
                    if (is_array($imagesRaw)) {
                        $imagesString = implode(' | ', array_map(fn($img) => $baseUrl . ltrim($img, '/'), $imagesRaw));
                    } elseif (is_string($imagesRaw)) {
                        $imagesArray = json_decode($imagesRaw, true);
                        $imagesString = is_array($imagesArray)
                            ? implode(' | ', array_map(fn($img) => $baseUrl . ltrim($img, '/'), $imagesArray))
                            : $baseUrl . ltrim($imagesRaw, '/');
                    } else {
                        $imagesString = '';
                    }

                    $mainImage       = $product->image ? $baseUrl . ltrim($product->image, '/') : '';
                    $categoryName    = $product->category?->name ?? '';
                    $productTypeName = $product->type?->name ?? '';

                    if ($product->variants->isEmpty()) {
                        fputcsv($output, [
                            $product->id,
                            $product->user_id,
                            $product->name,
                            $product->category_id,
                            $categoryName,
                            $product->product_type_id,
                            $productTypeName,
                            strip_tags($product->description ?? ''),
                            $mainImage,
                            $imagesString,
                            $product->status ? 'Active' : 'Inactive',
                            $product->created_at,
                            '', '', '', '', '', '', '', '', '',
                        ]);
                    } else {
                        foreach ($product->variants as $variant) {
                            fputcsv($output, [
                                $product->id,
                                $product->user_id,
                                $product->name,
                                $product->category_id,
                                $categoryName,
                                $product->product_type_id,
                                $productTypeName,
                                strip_tags($product->description ?? ''),
                                $mainImage,
                                $imagesString,
                                $product->status ? 'Active' : 'Inactive',
                                $product->created_at,
                                $variant->id,
                                $variant->product_id,
                                $variant->sku,
                                $variant->name,
                                $variant->price,
                                $variant->stock,
                                $variant->attribute_id,
                                $variant->attributeValues->pluck('value')->implode(', '),
                                $variant->created_at,
                            ]);
                        }
                    }
                }
            });

        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return response($csvContent, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }


    /* public function importCsv(Request $request)
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '256M');

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file   = $request->file('csv_file');
        $userId = session('admin_id');

        if (!$userId) {
            return back()->with('error', 'Session expired. Please login again.');
        }

        $handle = fopen($file->getRealPath(), 'r');

        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        fgetcsv($handle);

        $processedProducts = [];

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {

                if (count($row) < 15) continue;

                $productName      = trim($row[2]);
                $categoryName     = trim($row[4]);
                $productTypeName  = trim($row[6]);
                $description      = trim($row[7]);
                $mainImageUrl     = trim($row[8]);
                $allImagesUrl     = trim($row[9]);
                $status           = strtolower(trim($row[10])) === 'active' ? 1 : 0;
                $variantSku       = trim($row[14]);
                $variantName      = trim($row[15]);
                $variantPrice     = trim($row[16]);
                $variantStock     = trim($row[17]);
                $csvAttributeId   = trim($row[18]);
                $attributeValue   = trim($row[19]);

                if (empty($productName)) continue;

                $categoryId = null;
                if (!empty($categoryName)) {
                    $category = Category::firstOrCreate(
                        ['name' => $categoryName],
                        [
                            'slug'   => Str::slug($categoryName),
                            'status' => 1,
                        ]
                    );
                    $categoryId = $category->id;
                }

                $productTypeId = null;
                if (!empty($productTypeName)) {
                    $productType = ProductType::firstOrCreate(
                        ['name' => $productTypeName],
                        ['slug' => Str::slug($productTypeName)]
                    );
                    $productTypeId = $productType->id;
                } else {
                    $productType = ProductType::firstOrCreate(
                        ['name' => 'Default'],
                        ['slug' => 'default']
                    );
                    $productTypeId = $productType->id;
                }

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

                $cacheKey = $userId . '_' . $productName;

                if (!isset($processedProducts[$cacheKey])) {
                    $product = Product::firstOrCreate(
                        [
                            'user_id' => $userId,
                            'name'    => $productName,
                        ],
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

                if (empty($variantSku) && empty($variantName)) continue;

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
                                $newAttrValue = AttributeValue::create([
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
                                $newAttribute = Attribute::firstOrCreate(['name' => 'General']);
                                $newAttrValue = AttributeValue::create([
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
            }

            if (isset($handle) && is_resource($handle)) {
                fclose($handle);
            }

            DB::commit();

            return redirect()->route('admin_product')->with('success', 'Products imported successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($handle) && is_resource($handle)) {
                fclose($handle);
            }
            return back()->with('error', 'Import failed: ' . $e->getMessage());
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
                'http' => [
                    'timeout'    => 10,
                    'user_agent' => 'Mozilla/5.0',
                ],
                'ssl' => [
                    'verify_peer'      => false,
                    'verify_peer_name' => false,
                ]
            ]);

            $contents = @file_get_contents($url, false, $context);
            if ($contents === false) return null;

            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
            $extension = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp', 'gif'])
                ? strtolower($extension)
                : 'jpg';

            $filename = $folder . '/' . uniqid('img_', true) . '.' . $extension;
            Storage::disk('public')->put($filename, $contents);

            return $filename;

        } catch (\Exception $e) {
            return null;
        }
    } */

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file   = $request->file('csv_file');
        $userId = session('admin_id');

        if (!$userId) {
            return back()->with('error', 'Session expired. Please login again.');
        }

        $handle = fopen($file->getRealPath(), 'r');

        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        fgetcsv($handle);

        $allRows = [];
        while (($row = fgetcsv($handle)) !== false) {
            $allRows[] = $row;
        }

        if (isset($handle) && is_resource($handle)) {
            fclose($handle);
        }

        $batchSize    = 20;
        $batches      = array_chunk($allRows, $batchSize);
        $totalRows    = count($allRows);
        $totalBatches = count($batches);

        $importBatch = ImportBatch::create([
            'user_id'           => $userId,
            'filename'          => $file->getClientOriginalName(),
            'total_rows'        => $totalRows,
            'total_batches'     => $totalBatches,
            'processed_batches' => 0,
            'imported_rows'     => 0,
            'failed_rows'       => 0,
            'status'            => 'pending',
            'errors'            => null,
        ]);

        foreach ($batches as $index => $batchRows) {
            ProcessProductImportBatch::dispatch(
                $batchRows,
                $userId,
                $importBatch->id,
                $index + 1
            );
        }

        return redirect()->route('products.import.process', ['batch_id' => $importBatch->id]);
    }

    public function processImport(Request $request)
    {
        $batchId = $request->query('batch_id');

        \Artisan::call('queue:work', [
            '--stop-when-empty' => true,
            '--tries'           => 3,
            '--timeout'         => 300,
            '--queue'           => 'default',
        ]);

        return redirect()->route('admin_product')
            ->with('success', 'Import completed successfully!');
    }

    public function showImportForm()
    {
        return view('admin.products.importcsv');
    }
	
	public function search(Request $request)
	{
		$query = $request->input('q');
		
		$words = explode(' ', trim($query));

		$products = Product::where('status', 1)
			->where(function($q) use ($words) {
				foreach ($words as $word) {
					$trimmedWord = rtrim($word, 's');

					$q->where(function($sub) use ($word, $trimmedWord) {
						$sub->where('name', 'LIKE', "%{$word}%")
							->orWhere('name', 'LIKE', "%{$trimmedWord}%")
							->orWhere('description', 'LIKE', "%{$word}%")
							->orWhereHas('category', function($cat) use ($word, $trimmedWord) {
								$cat->where('name', 'LIKE', "%{$word}%")
								   ->orWhere('name', 'LIKE', "%{$trimmedWord}%");
							})
							->orWhereHas('type', function($type) use ($word, $trimmedWord) {
								$type->where('name', 'LIKE', "%{$word}%")
									->orWhere('name', 'LIKE', "%{$trimmedWord}%");
							});
					});
				}
			})
			->with(['firstVariant'])
			->paginate(12);

		return view('frontend.search_result', compact('products', 'query'));
	}

}