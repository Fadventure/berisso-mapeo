<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessImage;
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
        // Validación con coordenadas incluidas
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:2000',
            'address' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'hours' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'email_lugar' => 'nullable|email|max:255',
            'facebook' => 'nullable|url|max:255', 
            'instagram' => 'nullable|url|max:255',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'main_image_url' => 'nullable|url|max:255',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_urls.*' => 'nullable|url|max:255',
        ]);

        // Procesar imagen principal
        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('businesses', 'public');
            $data['image'] = $path;
        } elseif ($request->filled('main_image_url')) {
            $data['image'] = $request->input('main_image_url');
        } else {
            $data['image'] = null;
        }

        $data['user_id'] = Auth::id();
        $data['slug'] = $this->makeUniqueSlug($data['name']);

        // Crear el negocio (incluye latitude y longitude automáticamente)
        $business = Business::create($data);

        // Procesar galería de imágenes extras
        $galleryImages = [];

        // Procesar archivos subidos
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                if ($file && $file->isValid()) {
                    $galleryImages[] = $file->store('businesses/gallery', 'public');
                }
            }
        }

        // Procesar URLs de galería
        if ($request->filled('gallery_urls')) {
            foreach ($request->input('gallery_urls') as $url) {
                if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
                    $galleryImages[] = $url;
                }
            }
        }

        // Guardar en la tabla business_images
        foreach ($galleryImages as $index => $imageUrl) {
            BusinessImage::create([
                'business_id' => $business->id,
                'image_url' => $imageUrl,
                'order' => $index,
            ]);
        }

        $message = '¡Tu negocio se publicó correctamente!';
        if (count($galleryImages) > 0) {
            $message .= ' Se agregaron ' . count($galleryImages) . ' imágenes extras.';
        }
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $message .= ' La ubicación se guardó con precisión.';
        }

        return redirect()->route('home')->with('success', $message);
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