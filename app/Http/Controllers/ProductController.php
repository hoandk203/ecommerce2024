<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'manufacturing_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:manufacturing_date',
        ]);

        $product = new Product($request->all());

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        $product->save();

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công.');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    //Người bình thường
    public function show_normal(Product $product)
    {
        // Trả về view cho người dùng bình thường
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product->fill($request->except('image'));

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        $product->save();

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    // vừa sửa từ hàm search thành hàm thaiviethoan
    public function thaiviethoan(Request $request)
    {
        $query = $request->input('query');
        $priceRange = $request->input('price_range');
        $category = $request->input('category');

        $productsQuery = Product::query();

        if ($query) {
            $productsQuery->where('name', 'like', "%{$query}%");
        }

        if ($category) {
            $productsQuery->where('category_id', $category);
        }

        if ($priceRange) {
            list($min, $max) = explode('-', $priceRange);
            if ($max == '+') {
                $productsQuery->where('price', '>=', $min);
            } else {
                $productsQuery->whereBetween('price', [$min, $max]);
            }
        }

        $products = $productsQuery->paginate(12);
        $categories = Category::all();

        return view('welcome', compact('products', 'categories', 'query', 'priceRange'));
    }
}
