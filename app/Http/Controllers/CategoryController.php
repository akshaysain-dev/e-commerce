<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function view(){
        $categories = Category::orderBy('position')->paginate(8);
        return view('admin.categories.index', compact('categories'));
    }

    public function create(){
        return view('admin.categories.create');
    }
    public function add_category(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
            'slug' => 'required|unique:categories,slug',
        ]);

        $maxPosition = Category::max('position') ?? 0;

        Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'status' => $request->has('status') ? 1 : 0,
            'position' => $maxPosition + 1 
        ]);

        return redirect()->route('admin_category')
            ->with('success', 'Category added successfully!');
    }

    public function delete($id){
        $category = Category::find($id);
        
        if ($category) {
            $category->delete();
            return back()->with('success', 'Category deleted successfully!');
        }

        return back()->with('error', 'Record not found.');
    }

    public function edit($id){
        $category = Category::find($id);
        return view('admin.categories.edit_category', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required',
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status =  $request->has('status') ? 1 : 0;
        $category->description = $request->description;
        $category->save();

        return redirect()->route('admin_category')->with('success', 'Category updated successfully!');
    }

    public function view_category($id, $name)
    {
        $category = Category::findOrFail($id);

        $products = Product::where('category_id', $id)->where('status',1)->latest()->paginate(12);

        return view('frontend.category', compact('category', 'products'));
    }

    public function updateOrder(Request $request)
    {
        try {
            foreach ($request->order as $item) {
                Category::where('id', $item['id'])
                    ->update(['position' => $item['position']]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Order updated successfully'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required'
        ]);

        try {
            switch ($request->action) {

                case 'delete':
                    Category::whereIn('id', $request->ids)->delete();
                    $message = "Categories deleted successfully";
                    break;

                case 'activate':
                    Category::whereIn('id', $request->ids)
                        ->update(['status' => 1]);
                    $message = "Categories activated";
                    break;

                case 'deactivate':
                    Category::whereIn('id', $request->ids)
                        ->update(['status' => 0]);
                    $message = "Categories deactivated";
                    break;

                default:
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid action'
                    ]);
            }

            return response()->json([
                'status' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
