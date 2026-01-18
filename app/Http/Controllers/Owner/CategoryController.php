<?php

// ============================================================
// File: app/Http/Controllers/Owner/CategoryController.php
// Jalankan: php artisan make:controller Owner/CategoryController --resource
// Owner bisa manage categories seperti Admin
// ============================================================

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status);
        }

        $perPage = $request->input('per_page', 10);
        $categories = $query->latest()->paginate($perPage)->withQueryString();

        return view('owner.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('owner.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = true;

        Category::create($validated);

        return redirect()->route('owner.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function show(Category $category)
    {
        return view('owner.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('owner.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'is_active' => ['boolean']
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('owner.categories.index')
            ->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('owner.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }

    public function toggleStatus(Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        $status = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Kategori berhasil {$status}!");
    }
}

