<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('products')->latest()->paginate(20);

        $groupedProducts = Product::with('category')
            ->orderBy('name')
            ->get()
            ->groupBy(fn($p) => $p->category?->name ?? 'Uncategorized');

        $tagProducts = Tag::with('products:id')->get()
            ->keyBy('id')
            ->map(fn($t) => $t->products->pluck('id')->toArray());

        return view('admin.tags.index', compact('tags', 'groupedProducts', 'tagProducts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:60|unique:tags,name',
            'color' => 'nullable|string|max:7',
        ]);

        Tag::create([
            'name'  => $request->name,
            'slug'  => Str::slug($request->name),
            'color' => $request->color ?? '#6366f1',
        ]);

        return back()->with('success', 'Tag created!');
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name'  => 'required|string|max:60|unique:tags,name,' . $tag->id,
            'color' => 'nullable|string|max:7',
        ]);

        $tag->update([
            'name'  => $request->name,
            'slug'  => Str::slug($request->name),
            'color' => $request->color ?? $tag->color,
        ]);

        return back()->with('success', 'Tag updated!');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return back()->with('success', 'Tag deleted!');
    }

    public function assignProducts(Request $request, Tag $tag)
    {
        $request->validate([
            'products'   => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $tag->products()->sync($request->products ?? []);

        $count = count($request->products ?? []);
        return back()->with('success', $count . ' products assigned to "' . $tag->name . '".');
    }
}