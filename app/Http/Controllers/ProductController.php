<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a new product
     */
    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category' => 'required|string|in:engine-parts,brake-system,electrical,suspension,transmission',
            'product_image' => 'nullable|image|max:2048' // 2MB max
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = Str::slug($validatedData['name']) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');
        }

        // Create the product
        $product = Product::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'] ?? '',
            'price' => $validatedData['price'],
            'stock_quantity' => $validatedData['stock_quantity'],
            'category' => $validatedData['category'],
            'product_image' => $imagePath
        ]);

        // Redirect back with success message
        return redirect()->route('admin.dashboard')
            ->with('success', 'Product created successfully!');
    }

    /**
     * List all products
     */
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show edit form for a product
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.product-edit', compact('product'));
    }

    /**
     * Update a product
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category' => 'required|string|in:engine-parts,brake-system,electrical,suspension,transmission',
            'product_image' => 'nullable|image|max:2048'
        ]);

        // Handle image upload
        if ($request->hasFile('product_image')) {
            // Delete old image if exists
            if ($product->product_image) {
                Storage::disk('public')->delete($product->product_image);
            }

            $image = $request->file('product_image');
            $imageName = Str::slug($validatedData['name']) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');
            $validatedData['product_image'] = $imagePath;
        }

        // Update the product
        $product->update($validatedData);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Delete a product
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete product image if exists
        if ($product->product_image) {
            Storage::disk('public')->delete($product->product_image);
        }

        // Delete the product
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
