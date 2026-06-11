<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $query = Business::with('category');

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $selectedCategory = null;
        if ($request->filled('category')) {
            $selectedCategory = Category::where('slug', $request->query('category'))->first();
            if ($selectedCategory) {
                $query->where('category_id', $selectedCategory->id);
            }
        }

        $businesses = $query->where('published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('businesses.index', compact('businesses', 'categories', 'selectedCategory'));
    }

    public function show(Business $business)
    {
        $business->load(['category', 'user', 'images']);
        return view('businesses.show', compact('business'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('businesses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validación actualizada: ahora acepta archivo o URL
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:2000',
            'address' => 'required|string|max:255',
            'hours' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'email_lugar' => 'nullable|email|max:255',
            'facebook' => 'nullable|url|max:255', 
            'instagram' => 'nullable|url|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',  // NUEVO: archivo local
            'image_url' => 'nullable|url|max:255',  // NUEVO: URL externa
        ]);

        // Procesar la imagen: prioridad al archivo subido
        if ($request->hasFile('image_file')) {
            // Guardar el archivo y obtener su ruta
            $path = $request->file('image_file')->store('businesses', 'public');
            $data['image'] = $path;
        } elseif ($request->filled('image_url')) {
            // Usar la URL proporcionada
            $data['image'] = $request->input('image_url');
        } else {
            $data['image'] = null;
        }

        $data['user_id'] = Auth::id();
        $data['slug'] = $this->makeUniqueSlug($data['name']);

        Business::create($data);

        return redirect()->route('home')
            ->with('success', 'Tu negocio se publicó correctamente.');
    }

    protected function makeUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (Business::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}