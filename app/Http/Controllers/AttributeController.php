<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeController extends Controller
{

    public function index() {
        $attributes = Attribute::with('values')->get();
        return view('admin.products.add_variants', compact('attributes'));
    }

    public function storeAttribute(Request $request) {
        $request->validate(['name' => 'required|unique:attributes,name']);
        Attribute::create(['name' => $request->name]);
        return back()->with('success', 'Attribute created!');
    }

    public function storeValue(Request $request) {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required'
        ]);
        AttributeValue::create([
            'attribute_id' => $request->attribute_id,
            'value' => $request->value
        ]);
        return back()->with('success', 'Value added!');
    }

}
