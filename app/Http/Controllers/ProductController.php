<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\IkmProfile;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('ikmProfile');
        if ($request->filled('jenis_batu')) $query->where('jenis_batu', $request->jenis_batu);
        if ($request->filled('ikm')) $query->where('ikm_profile_id', $request->ikm);
        if ($request->filled('search')) $query->where('nama_produk', 'like', '%'.$request->search.'%');
        $products = $query->latest()->paginate(12)->appends($request->query());
        $ikmProfiles = IkmProfile::all();
        return view('produk.index', compact('products', 'ikmProfiles'));
    }

    public function create()
    {
        $ikmProfiles = IkmProfile::all();
        return view('produk.create', compact('ikmProfiles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ikm_profile_id' => 'required|exists:ikm_profiles,id',
            'nama_produk' => 'required|string|max:255',
            'jenis_batu' => 'required|string',
            'deskripsi' => 'nullable|string',
            'harga' => 'nullable|numeric',
            'gambar' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,svg,bmp,avif|max:20480',
            'gambar_url' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ], [
            'ikm_profile_id.required' => 'Pilih Sentra IKM pembuat produk.',
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'jenis_batu.required' => 'Kategori bahan/batu wajib diisi.',
            'gambar.mimes' => 'Format foto harus berupa JPG, PNG, WEBP, atau format gambar lainnya.',
            'gambar.max' => 'Ukuran foto maksimal 20 MB.',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('products', $filename, 'public');
            $validated['gambar_path'] = $path;
        } elseif ($request->filled('gambar_url')) {
            $validated['gambar_path'] = $request->gambar_url;
        }

        unset($validated['gambar'], $validated['gambar_url']);
        $product = Product::create($validated);

        if ($request->input('source') === 'catalog') {
            return redirect()->route('catalog')->with('success', 'Produk katalog berhasil ditambahkan.');
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load('ikmProfile', 'galleries', 'productionRecords');
        return view('produk.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $ikmProfiles = IkmProfile::all();
        return view('produk.edit', compact('product', 'ikmProfiles'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'ikm_profile_id' => 'required|exists:ikm_profiles,id',
            'nama_produk' => 'required|string|max:255',
            'jenis_batu' => 'required|string',
            'deskripsi' => 'nullable|string',
            'harga' => 'nullable|numeric',
            'gambar' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,svg,bmp,avif|max:20480',
            'gambar_url' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ], [
            'ikm_profile_id.required' => 'Pilih Sentra IKM pembuat produk.',
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'jenis_batu.required' => 'Kategori bahan/batu wajib diisi.',
            'gambar.mimes' => 'Format foto harus berupa JPG, PNG, WEBP, atau gambar lainnya.',
            'gambar.max' => 'Ukuran foto maksimal 20 MB.',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('products', $filename, 'public');
            $validated['gambar_path'] = $path;
        } elseif ($request->filled('gambar_url')) {
            $validated['gambar_path'] = $request->gambar_url;
        }

        unset($validated['gambar'], $validated['gambar_url']);
        $product->update($validated);

        if ($request->input('source') === 'catalog') {
            return redirect()->route('catalog')->with('success', "Foto dan data produk {$product->nama_produk} di katalog berhasil diperbarui!");
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Produk berhasil dihapus dari katalog.');
    }

    public function catalog(Request $request)
    {
        $query = Product::with('ikmProfile')->where('is_active', true);
        if ($request->filled('jenis_batu')) $query->where('jenis_batu', $request->jenis_batu);
        if ($request->filled('ikm')) $query->where('ikm_profile_id', $request->ikm);
        if ($request->filled('search')) $query->where('nama_produk', 'like', '%'.$request->search.'%');
        $products = $query->latest()->paginate(12)->appends($request->query());
        $ikmProfiles = IkmProfile::all();
        return view('catalog.index', compact('products', 'ikmProfiles'));
    }
}
