<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserProductController extends Controller
{
    /**
     * Display a listing of products for users.
     */
    public function index(Request $request)
    {
        // Fetch products with pagination, defaulting to an empty collection if no products
        $products = Product::query()
            // Apply category filter if provided
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category', $request->input('category'));
            })
            // Apply search filter if provided
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('search') . '%');
            })
            // Paginate with 9 products per page (3x3 grid), or return empty collection
            ->paginate(9);

        // Get unique categories for filter, defaulting to an empty collection
        $categories = Product::distinct('category')->pluck('category') ?? collect();

        // Ensure view always receives non-null values
        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $request->input('category', null),
            'searchQuery' => $request->input('search', null)
        ]);
    }

    /**
     * Display a specific product details.
     */
    public function show(string $id)
    {
        try {
            // Find the product or fail with a 404 error
            $product = Product::findOrFail($id);
            return view('products.show', compact('product'));
        } catch (ModelNotFoundException $e) {
            // If no product is found, this will automatically throw a 404 exception
            abort(404);
        }
    }
}
