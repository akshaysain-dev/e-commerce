<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\AttributeValue;
use App\Models\Attribute;
use App\Models\ProductVariant;
use App\Models\ProductType;
use App\Models\Margin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class VendorProductController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Vendor Products
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $vendor = Auth::guard('vendor')->user();

        $products = Product::with(['category', 'firstVariant'])
            ->where('user_id', $vendor->id)
            ->latest()
            ->paginate(10);

        return view('vendor.products.index', compact('products'));
    }

    /*
    |--------------------------------------------------------------------------
    | Create Product Page
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $categories = Category::all();
        $attributes = Attribute::all();
        $attributeValues = AttributeValue::all();
        $types = ProductType::all();

        return view(
            'vendor.products.create',
            compact(
                'categories',
                'attributes',
                'attributeValues',
                'types'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store Product
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'category_id' => 'required|exists:categories,id',

            'product_type_id' => 'required|exists:product_types,id',

            'name' => 'required|string|max:255',

            'description' => 'nullable|string',

            'image' => 'nullable|image|max:2048',

            'images.*' => 'nullable|image|max:2048',

            'variants.*.attribute_value_id' => 'required',

        ]);

        $vendor = Auth::guard('vendor')->user();

        /*
        |--------------------------------------------------------------------------
        | Create Product
        |--------------------------------------------------------------------------
        */

        $product = new Product();

        $product->category_id = $request->category_id;

        $product->product_type_id = $request->product_type_id;

        $product->user_id = $vendor->id;

        $product->name = $request->name;

        $product->description = $request->description;

        $product->status = 1;

        /*
        |--------------------------------------------------------------------------
        | Margin
        |--------------------------------------------------------------------------
        */

        $margin = Margin::where(
            'type_id',
            $product->product_type_id
        )->first();

        $margin_percentage = $margin->percentage ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Main Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            $path = $request->file('image')
                ->store('products', 'public');

            $product->image = $path;
        }

        /*
        |--------------------------------------------------------------------------
        | Multiple Images
        |--------------------------------------------------------------------------
        */

        $additionalImages = [];

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $img) {

                $additionalImages[] = $img->store(
                    'products',
                    'public'
                );
            }
        }

        $product->images = $additionalImages;

        $product->save();

        /*
        |--------------------------------------------------------------------------
        | Variants
        |--------------------------------------------------------------------------
        */

        if ($request->has('variants')) {

            foreach ($request->variants as $variantData) {

                $attrValue = AttributeValue::find(
                    $variantData['attribute_value_id']
                );

                $variantName = $attrValue
                    ? $attrValue->value
                    : 'Default';

                $skuBase = Str::slug(
                    $product->name . '-' . $variantName
                );

                $uniqueSku = strtoupper(
                    $skuBase . '-' . Str::random(4)
                );

                $price = $variantData['price'] ?? 0;

                $marginPrice = $price * (
                    1 + ($margin_percentage / 100)
                );

                $variant = $product->variants()->create([

                    'name' => $variantName,

                    'price' => $price,

                    'margin_price' => $marginPrice,

                    'stock' => $variantData['stock'] ?? 0,

                    'sku' => $uniqueSku,

                    'attribute_id' => $variantData['attribute_id'],

                ]);

                /*
                |--------------------------------------------------------------------------
                | Attach Attribute Value
                |--------------------------------------------------------------------------
                */

                if (!empty($variantData['attribute_value_id'])) {

                    $variant->attributeValues()->attach(
                        $variantData['attribute_value_id']
                    );
                }
            }
        }

        return redirect()
            ->route('vendor_product')
            ->with('success', 'Product Added Successfully');
    }


    public function delete($id)
    {
        $vendor = Auth::guard('vendor')->user();

        $product = Product::where('id', $id)
            ->where('user_id', $vendor->id)
            ->first();

        if ($product) {


            if ($product->image && Storage::disk('public')->exists($product->image)) {

                Storage::disk('public')->delete($product->image);
            }

            if (!empty($product->images)) {

                foreach ($product->images as $img) {

                    if (Storage::disk('public')->exists($img)) {

                        Storage::disk('public')->delete($img);
                    }
                }
            }

            foreach ($product->variants as $variant) {

                $variant->attributeValues()->detach();

                $variant->delete();
            }

            $product->delete();

            return back()->with('success', 'Product deleted successfully!');
        }

        return back()->with('error', 'Product not found.');
    }

    public function edit($id)
    {
        $vendor = Auth::guard('vendor')->user();

        $product = Product::where('id', $id)
            ->where('user_id', $vendor->id)
            ->firstOrFail();

        $categories = Category::all();

        $attributes = Attribute::all();

        $attributeValues = AttributeValue::all();

        $types = ProductType::all();

        $tags = \App\Models\Tag::orderBy('name')->get();

        session(['vendor_products_url' => url()->previous()]);

        return view(
            'vendor.products.edit',
            compact(
                'product',
                'categories',
                'attributes',
                'attributeValues',
                'types',
                'tags'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $vendor = Auth::guard('vendor')->user();

        $request->validate([

            'category_id' => 'required|exists:categories,id',

            'product_type_id' => 'required|exists:product_types,id',

            'name' => 'required|string|max:255',

            'description' => 'nullable|string',

            'image' => 'nullable|image|max:2048',

            'status' => 'nullable|boolean',

            'variants.*.sku' => 'nullable|string',

            'variants.*.price' => 'nullable|numeric',

            'variants.*.attribute_value_id' => 'nullable|exists:attribute_values,id',

        ]);

        $product = Product::where('id', $id)
            ->where('user_id', $vendor->id)
            ->firstOrFail();

        $product->category_id = $request->category_id;

        $product->name = $request->name;

        $product->description = $request->description;

        $product->status = $request->has('status') ? 1 : 0;

        $product->product_type_id = $request->product_type_id;

        $product->tags()->sync($request->tags ?? []);

        $margin = Margin::where(
            'type_id',
            $product->product_type_id
        )->first();

        if (!$margin) {

            return back()->withErrors([
                'msg' => 'Margin not found for this product type.'
            ]);
        }

        $margin_percentage = $margin->percentage;

        if ($request->hasFile('image')) {

            if (
                $product->image &&
                Storage::disk('public')->exists($product->image)
            ) {

                Storage::disk('public')->delete($product->image);
            }

            $product->image = $request->file('image')
                ->store('products', 'public');
        }

        $currentImages = $product->images ?? [];

        if ($request->has('remove_images')) {

            foreach ($request->remove_images as $index) {

                if (isset($currentImages[$index])) {

                    if (
                        Storage::disk('public')->exists($currentImages[$index])
                    ) {

                        Storage::disk('public')->delete($currentImages[$index]);
                    }

                    unset($currentImages[$index]);
                }
            }

            $currentImages = array_values($currentImages);
        }

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $img) {

                $currentImages[] = $img->store(
                    'products',
                    'public'
                );
            }
        }

        $product->images = $currentImages;

        $product->save();

        if ($request->has('variants')) {

            foreach ($request->variants as $variantData) {

                if (!empty($variantData['attribute_value_id'])) {

                    $attrValue = AttributeValue::find(
                        $variantData['attribute_value_id']
                    );

                    $variantName = $attrValue
                        ? $attrValue->value
                        : 'Variant';

                    $skuBase = Str::slug(
                        $product->name . '-' . $variantName
                    );

                    $uniqueSku = strtoupper(
                        $skuBase . '-' . Str::random(4)
                    );

                    $marginPrice = ($variantData['price']) * (
                        1 + ($margin_percentage / 100)
                    );

                    $data = [

                        'name' => $variantName,

                        'price' => $variantData['price']
                            ?? $product->price,

                        'margin_price' => $marginPrice
                            ?? $product->price,

                        'stock' => $variantData['stock'] ?? 0,

                        'attribute_id' => $variantData['attribute_id'] ?? 0,
                    ];

                    $existingVariant = isset($variantData['id'])

                        ? ProductVariant::find($variantData['id'])

                        : null;

                    if (
                        !$existingVariant ||
                        empty($existingVariant->sku)
                    ) {

                        $data['sku'] = $uniqueSku;
                    }

                    $variant = $product->variants()->updateOrCreate(

                        ['id' => $variantData['id'] ?? null],

                        $data
                    );

                    $variant->attributeValues()->sync([
                        $variantData['attribute_value_id']
                    ]);
                }
            }
        }


        if ($request->has('delete_variants')) {

            foreach ($request->delete_variants as $delete_variant) {

                $variant = ProductVariant::find($delete_variant);

                if ($variant) {

                    $variant->attributeValues()->detach();

                    $variant->delete();
                }
            }
        }

        $returnUrl = session(
            'vendor_products_url',
            route('vendor_product')
        );

        return redirect($returnUrl)
            ->with('success', 'Product updated successfully!');
    }
}