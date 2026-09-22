<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display products.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $search = $request->input('search');
        $status = $request->input('status');

        $query = Product::with('category');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query
                    ->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'sku',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($status === 'active') {
            $query->where(
                'is_active',
                true
            );
        }

        if ($status === 'inactive') {
            $query->where(
                'is_active',
                false
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        */

        $products = $query
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalProducts = Product::count();

        $activeProducts = Product::where(
            'is_active',
            true
        )->count();

        $inactiveProducts = Product::where(
            'is_active',
            false
        )->count();

        $totalProductValue = Product::where(
            'is_active',
            true
        )->sum('selling_price');

        return view('products.index', [
            'user' => $user,
            'products' => $products,
            'totalProducts' => $totalProducts,
            'activeProducts' => $activeProducts,
            'inactiveProducts' => $inactiveProducts,
            'totalProductValue' => $totalProductValue,
            'search' => $search,
            'status' => $status,
        ]);
    }

    /**
     * Show Add Product page.
     */
    public function create(Request $request): View
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        if (!in_array(
            $user->role,
            ['CEO/Admin'],
            true
        )) {
            abort(
                403,
                'You are not authorized to add products.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Product Categories Only
        |--------------------------------------------------------------------------
        |
        | Do NOT load inventory categories here.
        |
        | Product categories:
        | - Burgers
        | - Chicken Meals
        | - Rice Meals
        | - Pasta
        | - Sides
        | - Snacks
        | - Beverages
        | - Desserts
        | - Combos
        |
        */

        $categories = Category::where(
            'type',
            Category::TYPE_PRODUCT
        )
            ->where(
                'is_active',
                true
            )
            ->orderBy('name')
            ->get();

        return view('products.create', [
            'user' => $user,
            'categories' => $categories,
        ]);
    }

    /**
     * Store a new product.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        if (!in_array(
            $user->role,
            ['CEO/Admin'],
            true
        )) {
            abort(
                403,
                'You are not authorized to add products.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            /*
            | Only an active PRODUCT category can be selected.
            */

            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')
                    ->where(function ($query) {
                        $query
                            ->where(
                                'type',
                                Category::TYPE_PRODUCT
                            )
                            ->where(
                                'is_active',
                                true
                            );
                    }),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'sku' => [
                'required',
                'string',
                'max:100',
                'unique:products,sku',
            ],

            'selling_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Upload Product Image
        |--------------------------------------------------------------------------
        */

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request
                ->file('image')
                ->store(
                    'products',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Product
        |--------------------------------------------------------------------------
        */

        Product::create([
            'category_id' => $validated['category_id'] ?? null,
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'selling_price' => $validated['selling_price'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                'Product added successfully.'
            );
    }

    /**
     * Show Edit Product page.
     */
    public function edit(
        Request $request,
        Product $product
    ): View {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        if (!in_array(
            $user->role,
            ['CEO/Admin'],
            true
        )) {
            abort(
                403,
                'You are not authorized to edit products.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Product Categories Only
        |--------------------------------------------------------------------------
        */

        $categories = Category::where(
            'type',
            Category::TYPE_PRODUCT
        )
            ->where(
                'is_active',
                true
            )
            ->orderBy('name')
            ->get();

        return view('products.edit', [
            'user' => $user,
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    /**
     * Update product.
     */
    public function update(
        Request $request,
        Product $product
    ): RedirectResponse {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        if (!in_array(
            $user->role,
            ['CEO/Admin'],
            true
        )) {
            abort(
                403,
                'You are not authorized to edit products.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            /*
            | Only an active PRODUCT category can be selected.
            */

            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')
                    ->where(function ($query) {
                        $query
                            ->where(
                                'type',
                                Category::TYPE_PRODUCT
                            )
                            ->where(
                                'is_active',
                                true
                            );
                    }),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'sku' => [
                'required',
                'string',
                'max:100',
                Rule::unique(
                    'products',
                    'sku'
                )->ignore($product->id),
            ],

            'selling_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Product Data
        |--------------------------------------------------------------------------
        */

        $productData = [
            'category_id' => $validated['category_id'] ?? null,
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'selling_price' => $validated['selling_price'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'],
        ];

        /*
        |--------------------------------------------------------------------------
        | Replace Product Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            /*
            | Delete previous image.
            */

            if (!empty($product->image)) {
                Storage::disk('public')->delete(
                    $product->image
                );
            }

            /*
            | Store new image.
            */

            $productData['image'] = $request
                ->file('image')
                ->store(
                    'products',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Update Product
        |--------------------------------------------------------------------------
        */

        $product->update($productData);

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                'Product updated successfully.'
            );
    }
}