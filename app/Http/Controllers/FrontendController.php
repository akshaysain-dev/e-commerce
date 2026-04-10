<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Banner;
use App\Models\RecentView;
use App\Models\Rating;
use App\Models\Sale;
use App\Models\Tag;
use App\Services\SaleService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class FrontendController extends Controller
{
    public function __construct(private SaleService $saleService) {}

    public function home()
    {
        $banners     = Banner::where('type', 'main')->where('status', 1)->orderBy('sort_order', 'asc')->get();
        $sideBanners = Banner::where('type', 'side')->where('status', 1)->orderBy('sort_order', 'asc')->get();
        $categories  = Category::all();
        $products = Product::where('status', 1)->latest()->paginate(8);

        $recentProductIds = [];

        if (session('customer_id')) {
            $recentProductIds = RecentView::where('customer_id', session('customer_id'))
                ->pluck('product_id')
                ->toArray();
        } else {
            $cookieName = 'recent_products';
            if (isset($_COOKIE[$cookieName])) {
                $recentProductIds = json_decode(stripslashes($_COOKIE[$cookieName]), true) ?: [];
            }
        }

        if (!empty($recentProductIds)) {
            $recentProductIds = array_filter(
                array_map('intval', $recentProductIds),
                fn($id) => $id > 0
            );

            if (!empty($recentProductIds)) {
                $recentProductIds = array_reverse(array_unique($recentProductIds));

                $recentProducts = Product::whereIn('id', $recentProductIds)
                    ->where('status', 1)
                    ->orderByRaw("FIELD(id, " . implode(',', $recentProductIds) . ")")
                    ->take(10)
                    ->get();
            } else {
                $recentProducts = collect();
            }
        } else {
            $recentProducts = collect();
        }

        return view('frontend.home', compact(
				'products', 
				'categories', 
				'banners', 
				'sideBanners', 
				'recentProducts'
			));
    }

    public function showSingleProduct($id)
    {
        $product = Product::with([
                        'category',
                        'tags',
                        'variants.attributeValue.attribute',
                        'ratings.customer'
                    ])
                    ->where('status', 1)
                    ->findOrFail($id);
		$related_products = Product::where('category_id', $product->category_id)->where('id', '!=', $product->id)->latest()->take(6)->get();
        $categories = Category::all();

        $sale = $this->saleService->getActiveSaleForProduct($product);

        $ratings = $product->ratings()
                        ->with('customer')
                        ->latest()
                        ->paginate(5);

        $starCounts = $product->ratings()
                            ->selectRaw('rating, count(*) as total')
                            ->groupBy('rating')
                            ->pluck('total', 'rating');

        $avgRating    = round($product->ratings()->avg('rating'), 1);
        $totalRatings = $product->ratings()->count();

        $userRating = session('customer_id')
                    ? $product->ratings()->where('customer_id', session('customer_id'))->first()
                    : null;

        return view('frontend.view_product', compact(
            'product',
            'categories',
            'sale',
            'ratings',
            'starCounts',
            'avgRating',
            'totalRatings',
            'userRating',
			'related_products'
        ));
    }

    public function productsByCategory(Request $request)
    {
        try {
            $categoryId = $request->category_id;

            $query = Product::with(['category', 'firstVariant'])
                ->whereHas('firstVariant', function ($q) {
                    $q->whereNotNull('margin_price');
                })
                ->latest();

            if ($categoryId && $categoryId !== 'all') {
                $query->where('category_id', $categoryId);
            } else {
                $query->take(8);
            }

            $products = $query->get();

            $data = $products->map(function ($product) {
                $price = $product->firstVariant->margin_price ?? 0;

                return [
                    'id'                => $product->id,
                    'name'              => $product->name ?? 'Unnamed',
                    'short_description' => \Str::limit($product->description ?? '', 70),
                    'price'             => $price,
                    'formatted_price'   => number_format($price),
                    'stock'             => $product->firstVariant->stock ?? 0,
                    'image'             => $product->image ?? null,
                    'image_url'         => $product->image ? asset('storage/' . $product->image) : null,
                    'view_url'          => route('view_product', $product->id),
                    'category_name'     => optional($product->category)->name ?? 'General',
                    'is_wishlisted'     => auth()->check() ? $product->isWishlisted() : false,
                    'wishlisted_url'    => route('wishlist.toggle'),
                ];
            });

            return response()->json(['success' => true, 'products' => $data]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ], 500);
        }
    }

    public function salePage()
    {
        $activeSales = Sale::active()
                           ->with(['category', 'productType'])
                           ->get();

        if ($activeSales->isEmpty()) {
            return view('frontend.sales', [
                'activeSales'  => collect(),
                'saleProducts' => collect(),
                'maxDiscount'  => 0,
            ]);
        }

        $categoryIds    = $activeSales->where('scope', 'category')->pluck('scope_id');
        $productTypeIds = $activeSales->where('scope', 'product_type')->pluck('scope_id');
        $tagIds         = $activeSales->where('scope', 'tag')->pluck('scope_id');  // ← tag IDs

        $tagProductIds = collect();
        if ($tagIds->isNotEmpty()) {
            $tagProductIds = \DB::table('product_tag')
                ->whereIn('tag_id', $tagIds)
                ->pluck('product_id');
        }

        $saleProducts = Product::with(['category', 'variants', 'firstVariant', 'tags'])
            ->where(function ($q) use ($categoryIds, $productTypeIds, $tagProductIds) {

                $conditions = false;

                if ($categoryIds->isNotEmpty()) {
                    $q->whereIn('category_id', $categoryIds);
                    $conditions = true;
                }
                if ($productTypeIds->isNotEmpty()) {
                    $conditions
                        ? $q->orWhereIn('product_type_id', $productTypeIds)
                        : $q->whereIn('product_type_id', $productTypeIds);
                    $conditions = true;
                }
                if ($tagProductIds->isNotEmpty()) {
                    $conditions
                        ? $q->orWhereIn('id', $tagProductIds)
                        : $q->whereIn('id', $tagProductIds);
                }
            })
            ->get();

        $this->saleService->attachSalesToProducts($saleProducts);

        $saleProducts = $saleProducts->filter(fn($p) => $p->active_sale !== null);

        $maxDiscount = $activeSales->max('discount') ?? 0;

        $activeSales->each(function ($sale) use ($saleProducts) {
            $sale->product_count = $saleProducts
                ->filter(fn($p) => $p->active_sale && $p->active_sale->id === $sale->id)
                ->count();
        });

        return view('frontend.sales', compact(
            'activeSales',
            'saleProducts',
            'maxDiscount',
        ));
    }
}