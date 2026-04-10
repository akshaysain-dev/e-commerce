<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Margin;
use App\Models\ProductType;
use App\Models\Product;

class MarginController extends Controller
{
    //
	public function index()
	{
		$margins = Margin::with('productType')->get();
		$productTypes = ProductType::all();
		return view('admin.margins.index', compact('margins', 'productTypes'));
	}

	public function store(Request $request)
	{
		$request->validate([
			'type_id' => 'required|exists:product_types,id',
			'name' => 'required|string|max:255',
			'percentage' => 'required|numeric|min:0|max:100',
		]);

		$exists = Margin::where('type_id', $request->type_id)->exists();

		if (!$exists) {
			$margin = Margin::create($request->all());

			Product::where('product_type_id', $request->type_id)
				->with('variants')
				->chunk(100, function ($products) use ($margin) {
					foreach ($products as $product) {
						foreach ($product->variants as $variant) {
							$newMarginPrice = $variant->price * (1 + ($margin->percentage / 100));

							$variant->update([
								'margin_price' => $newMarginPrice,
							]);
						}
					}
				});

			return back()->with('success', 'Margin added and all related product prices updated!');
		} else {
			return back()->with('error', 'A margin is Already applied to this Product Type.');
		}
	}
	
	public function destroy($id)
	{
		$margin = Margin::findOrFail($id);

		Product::where('product_type_id', $margin->type_id)
			->with('variants')
			->chunk(100, function ($products) {
				foreach ($products as $product) {
					foreach ($product->variants as $variant) {
						$variant->update([
							'margin_price' => $variant->price,
						]);
					}
				}
			});

		$margin->delete();

		return back()->with('success', 'Margin removed and product prices reset successfully!');
	}

}
