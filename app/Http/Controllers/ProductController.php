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
        try {
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

            // Check if it's an API request
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Product created successfully!',
                    'product' => $product
                ], 201);
            }
            
            // For web requests, redirect back with a success message
            return redirect()->route('admin.products.index')
                             ->with('success', 'Product created successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            
            // For web requests, redirect back with validation errors
            return redirect()->back()
                             ->withErrors($e->errors())
                             ->withInput();
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Product creation error: ' . $e->getMessage());
            
            // Check if it's an API request
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unable to create product',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            // For web requests, redirect back with an error message
            return redirect()->back()
                             ->with('error', 'Unable to create product: ' . $e->getMessage())
                             ->withInput();
        }
    }

    /**
     * Render the list of products for admin view
     */
    public function adminIndex()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * List all products (JSON API endpoint)
     */
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10);
        return response()->json($products);
    }

    /**
     * Show edit form for a product
     */
    public function edit($id)
    {
        try {
            // Convert string ID to MongoDB ObjectId if needed
            if (is_string($id)) {
                $id = new \MongoDB\BSON\ObjectId($id);
            }

            // Log the ID being searched
            \Log::info('Searching for product with ID: ' . (string)$id);

            $product = Product::where('_id', $id)->first();
            
            // Log the found product details
            if ($product) {
                \Log::info('Product found', [
                    'id' => (string)$product->_id,
                    'name' => $product->name
                ]);
            } else {
                \Log::warning('No product found with ID: ' . (string)$id);
                
                // Fetch all products to help diagnose
                $allProducts = Product::all();
                \Log::info('All product IDs:', 
                    $allProducts->map(function($p) { 
                        return (string)$p->_id; 
                    })->toArray()
                );
            }

            // If no product found, throw an exception
            if (!$product) {
                throw new \Exception('Product not found');
            }

            return view('admin.product-edit', compact('product'));
        } catch (\Exception $e) {
            // Log the full error
            \Log::error('Product edit error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            
            // Redirect back with an error message
            return redirect()->route('admin.products.index')
                             ->with('error', 'Unable to find the product to edit. ' . $e->getMessage());
        }
    }

    /**
     * Update a product
     */
    public function update(Request $request, $id)
    {
        try {
            // Convert string ID to MongoDB ObjectId if needed
            if (is_string($id)) {
                $id = new \MongoDB\BSON\ObjectId($id);
            }

            $product = Product::where('_id', $id)->firstOrFail();

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

            // Check if it's an API request
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Product updated successfully!',
                    'product' => $product
                ], 200);
            }
            
            // For web requests, redirect back with a success message
            return redirect()->route('admin.products.index')
                             ->with('success', 'Product updated successfully');
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Product update error: ' . $e->getMessage());
            
            // Check if it's an API request
            if (request()->expectsJson()) {
                return response()->json([
                    'error' => 'Unable to update product',
                    'message' => $e->getMessage()
                ], 500);
            }
            
            // For web requests, redirect back with an error message
            return redirect()->back()
                             ->with('error', 'Unable to update product. ' . $e->getMessage())
                             ->withInput();
        }
    }

    /**
     * Show a specific product
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    /**
     * Delete a product
     */
    public function destroy($id)
    {
        try {
            // Convert string ID to MongoDB ObjectId if needed
            if (is_string($id)) {
                $id = new \MongoDB\BSON\ObjectId($id);
            }

            $product = Product::where('_id', $id)->firstOrFail();
            
            // Delete product image if it exists
            if ($product->product_image) {
                Storage::disk('public')->delete($product->product_image);
            }
            
            $product->delete();
            
            // Check if it's an API request
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Product deleted successfully'], 200);
            }
            
            // For web requests, redirect back with a success message
            return redirect()->route('admin.products.index')
                             ->with('success', 'Product deleted successfully');
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Product delete error: ' . $e->getMessage());
            
            // Check if it's an API request
            if (request()->expectsJson()) {
                return response()->json([
                    'error' => 'Unable to delete product',
                    'message' => $e->getMessage()
                ], 500);
            }
            
            // For web requests, redirect back with an error message
            return redirect()->route('admin.products.index')
                             ->with('error', 'Unable to delete product. ' . $e->getMessage());
        }
    }
}
