<!DOCTYPE html>
<html>
<body>
    <h1>Image Debug</h1>
    @php
        $products = App\Models\Product::whereNotNull('product_image')->get();
    @endphp

    <h2>Server Configuration</h2>
    <pre>
    APP_URL: {{ env('APP_URL') }}
    Storage Root: {{ storage_path('app/public') }}
    Public Storage Link: {{ public_path('storage') }}
    </pre>

    @foreach($products as $product)
        <div style="border: 1px solid black; margin: 10px; padding: 10px;">
            <h2>{{ $product->name }}</h2>
            <p><strong>Raw Path:</strong> {{ $product->product_image }}</p>
            <p><strong>Storage Path:</strong> {{ storage_path('app/public/' . $product->product_image) }}</p>
            <p><strong>Storage URL:</strong> {{ Storage::url($product->product_image) }}</p>
            <p><strong>Asset URL:</strong> {{ asset('storage/' . $product->product_image) }}</p>
            <img src="{{ Storage::url($product->product_image) }}" alt="{{ $product->name }}" style="max-width: 300px;">
            <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->name }}" style="max-width: 300px;">
        </div>
    @endforeach
</body>
</html>
