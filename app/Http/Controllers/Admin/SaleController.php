<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductType;
use App\Models\Product;
use App\Models\Tag;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['category', 'productType'])
                     ->latest()
                     ->paginate(15);

        return view('admin.sales.index', compact('sales'));
    }

    public function create()
    {
        $categories   = Category::orderBy('name')->get();
        $productTypes = ProductType::orderBy('name')->get();
        $tags         = Tag::withCount('products')->orderBy('name')->get();

        return view('admin.sales.create', compact('categories', 'productTypes', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'discount'  => 'required|numeric|min:0.01',
            'type'      => 'required|in:percent,fixed',
            'scope'     => 'required|in:category,product_type,tag',
            'scope_id'  => 'required|integer|min:1',
            'starts_at' => 'required|date',
            'ends_at'   => 'required|date|after:starts_at',
        ]);

        $this->validateScopeId($request);

        Sale::create([
            'name'      => $request->name,
            'discount'  => $request->discount,
            'type'      => $request->type,
            'scope'     => $request->scope,
            'scope_id'  => $request->scope_id,
            'starts_at' => $request->starts_at,
            'ends_at'   => $request->ends_at,
            'is_active' => true,
        ]);

        return redirect()->route('admin.sales.index')
                         ->with('success', 'Sale created successfully!');
    }

    public function edit(Sale $sale)
    {
        $categories   = Category::orderBy('name')->get();
        $productTypes = ProductType::orderBy('name')->get();
        $tags         = Tag::withCount('products')->orderBy('name')->get();

        return view('admin.sales.edit', compact('sale', 'categories', 'productTypes', 'tags'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'discount'  => 'required|numeric|min:0.01',
            'type'      => 'required|in:percent,fixed',
            'scope'     => 'required|in:category,product_type,tag',
            'scope_id'  => 'required|integer|min:1',
            'starts_at' => 'required|date',
            'ends_at'   => 'required|date|after:starts_at',
        ]);

        $this->validateScopeId($request);

        $sale->update([
            'name'      => $request->name,
            'discount'  => $request->discount,
            'type'      => $request->type,
            'scope'     => $request->scope,
            'scope_id'  => $request->scope_id,
            'starts_at' => $request->starts_at,
            'ends_at'   => $request->ends_at,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.sales.index')
                         ->with('success', 'Sale updated successfully!');
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();
        return back()->with('success', 'Sale deleted!');
    }


    private function validateScopeId(Request $request): void
    {
        match ($request->scope) {
            'category'     => $request->validate(['scope_id' => 'exists:categories,id']),
            'product_type' => $request->validate(['scope_id' => 'exists:product_types,id']),
            'tag'          => $request->validate(['scope_id' => 'exists:tags,id']),
        };
    }

    public static function getProductsForSale(Sale $sale)
    {
        return match ($sale->scope) {
            'category'     => Product::where('category_id', $sale->scope_id)->get(),
            'product_type' => Product::where('product_type_id', $sale->scope_id)->get(),
            'tag'          => Tag::findOrFail($sale->scope_id)->products,
            default        => collect(),
        };
    }
}