<?php
namespace App\Http\Controllers;

use App\Models\ProductType;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    public function index()
    {
        $types = ProductType::all();
        return view('admin.products.product_types', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|unique:product_types,slug'
        ]);

        ProductType::create($request->all());

        return back()->with('success', 'Product type added successfully!');
    }
}
