<?php

namespace App\Http\Controllers;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class BannerController extends Controller
{
    //
    public function index() {
        $banners = Banner::orderBy('sort_order', 'asc')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request) {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'type' => 'required|in:main,side',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image' => $path,
            'link' => $request->link,
            'type' => $request->type,
            'sort_order' => $request->sort_order ?? 0,
            'status' => 1,
        ]);

        return back()->with('success', 'Banner uploaded successfully!');
    }

    public function destroy($id) {
        $banner = Banner::findOrFail($id);
        
        if (Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }
        
        $banner->delete();
        return back()->with('success', 'Banner deleted!');
    }

    public function edit($id) {
        $banner = Banner::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, $id) {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'type' => 'required|in:main,side',
        ]);

        if ($request->hasFile('image')) {
            if (Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $banner->image = $request->file('image')->store('banners', 'public');
        }

        $banner->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'link' => $request->link,
            'type' => $request->type,
            'sort_order' => $request->sort_order,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('banners.index')->with('success', 'Banner updated successfully!');
    }
}
