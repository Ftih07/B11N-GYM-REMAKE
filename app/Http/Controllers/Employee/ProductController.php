<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\CategoryProduct;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Nampilin Daftar Produk (DENGAN FILTER & PAGINATION)
    public function index(Request $request)
    {
        $query = Product::with(['categoryproduct', 'store'])->latest();

        // 1. Filter Pencarian Nama / Deskripsi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 2. Filter Kategori
        if ($request->filled('category_products_id')) {
            $query->where('category_products_id', $request->category_products_id);
        }

        // 3. Filter Cabang / Toko
        if ($request->filled('stores_id')) {
            $query->where('stores_id', $request->stores_id);
        }

        // 4. Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Eksekusi Paginasi 10 per halaman
        $products = $query->paginate(10);

        // Ambil master data untuk mengisi dropdown filter di Blade
        $categories = CategoryProduct::all();
        $stores = Store::all();

        return view('employee.products.index', compact('products', 'categories', 'stores'));
    }

    // --- FUNGSI LAINNYA TETAP SAMA (JANGAN DIHAPUS) ---
    public function create()
    {
        $categories = CategoryProduct::all();
        $stores = Store::all();
        return view('employee.products.create', compact('categories', 'stores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'flavour' => 'nullable|string|max:255',
            'serving_option' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:ready,soldout',
            'category_products_id' => 'required|exists:category_products,id',
            'stores_id' => 'required|exists:stores,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('product', 'public');
        }

        Product::create($validated);
        return redirect()->route('employee.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    // --- GANTI FUNGSI EDIT ---
    public function edit($id)
    {
        // Cari manual pakai ID (Mencegah 404 karena UUID)
        $product = Product::findOrFail($id);

        $categories = CategoryProduct::all();
        $stores = Store::all();

        return view('employee.products.edit', compact('product', 'categories', 'stores'));
    }

    // --- GANTI FUNGSI UPDATE ---
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id); // Cari manual

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'flavour' => 'nullable|string|max:255',
            'serving_option' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:ready,soldout',
            'category_products_id' => 'required|exists:category_products,id',
            'stores_id' => 'required|exists:stores,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('product', 'public');
        }

        $product->update($validated);

        return redirect()->route('employee.products.index')->with('success', 'Data produk berhasil diperbarui!');
    }

    // --- GANTI FUNGSI DESTROY ---
    public function destroy($id)
    {
        $product = Product::findOrFail($id); // Cari manual

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('employee.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
