<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vendor;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VendorManagementController extends Controller
{

    public function index()
    {
        $vendors = Vendor::with([
                        'user',
                        'products'
                    ])
                    ->latest()
                    ->get();

        return view(
            'admin.vendor.index',
            compact('vendors')
        );
    }

    public function show($id)
    {
        $vendor = Vendor::with([
                        'user',
                        'products.variants',
                        'products.category'
                    ])
                    ->findOrFail($id);

        return view(
            'admin.vendor.show',
            compact('vendor')
        );
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $vendor = Vendor::findOrFail($id);

        $vendor->user->status = $request->status;

        $vendor->user->save();

        return back()->with(
            'success',
            'Vendor status updated successfully.'
        );
    }

    public function updateCommission(Request $request, $id)
    {
        $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100'
        ]);

        $vendor = Vendor::findOrFail($id);

        $vendor->commission_rate = $request->commission_rate;

        $vendor->save();

        return back()->with(
            'success',
            'Commission updated successfully.'
        );
    }

    public function updateProductStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $product = Product::findOrFail($id);

        $product->status = $request->status;

        $product->save();

        return back()->with(
            'success',
            'Product status updated successfully.'
        );
    }

    public function deleteProduct($id)
    {
        $product = Product::with('variants')
                    ->findOrFail($id);

        DB::beginTransaction();

        try {

            if (
                $product->image &&
                Storage::disk('public')->exists($product->image)
            ) {

                Storage::disk('public')->delete($product->image);
            }

            if (!empty($product->images)) {

                foreach ($product->images as $img) {

                    if (
                        Storage::disk('public')->exists($img)
                    ) {

                        Storage::disk('public')->delete($img);
                    }
                }
            }

            foreach ($product->variants as $variant) {

                $variant->attributeValues()->detach();

                $variant->delete();
            }

            $product->tags()->detach();

            $product->delete();

            DB::commit();

            return back()->with(
                'success',
                'Product deleted successfully.'
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function deleteVendor($id)
    {
        $vendor = Vendor::with([
                        'user',
                        'products.variants'
                    ])
                    ->findOrFail($id);

        DB::beginTransaction();

        try {

            foreach ($vendor->products as $product) {

                if (
                    $product->image &&
                    Storage::disk('public')->exists($product->image)
                ) {

                    Storage::disk('public')->delete($product->image);
                }

                if (!empty($product->images)) {

                    foreach ($product->images as $img) {

                        if (
                            Storage::disk('public')->exists($img)
                        ) {

                            Storage::disk('public')->delete($img);
                        }
                    }
                }


                foreach ($product->variants as $variant) {

                    $variant->attributeValues()->detach();

                    $variant->delete();
                }

                $product->tags()->detach();

                $product->delete();
            }

            $vendor->delete();


            if ($vendor->user) {

                $vendor->user->delete();
            }

            DB::commit();

            return back()->with(
                'success',
                'Vendor deleted successfully.'
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function showProducts($id)
    {
        $vendor = Vendor::with([
                        'user',
                        'products.variants',
                        'products.category'
                    ])->findOrFail($id);

        return view(
            'admin.vendor.show',
            compact('vendor')
        );
    }
}