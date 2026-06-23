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
        // SOLO NEGOCIOS APROBADOS Y PUBLICADOS
        $query = Business::with('category')
            ->where('status', 'approved')
            ->where('published', true);

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

        $businesses = $query->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('businesses.index', compact('businesses', 'categories', 'selectedCategory'));
    }

    public function show(Business $business)
    {
        // Si el negocio no está aprobado, mostrar error 404
        if ($business->status !== 'approved' || !$business->published) {
            abort(404, 'Este negocio no está disponible.');
        }
        
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
        // Validación - SOLO ARCHIVOS LOCALES (sin URLs)
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
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
    // ==========================================
    // VALIDACIÓN CONTRA DUPLICADOS
    // ==========================================
    // Verificar si el usuario ya tiene un negocio con el mismo nombre
    $existingBusiness = Business::where('user_id', Auth::id())
        ->where('name', $data['name'])
        ->first();
    
    if ($existingBusiness) {
        $statusText = $existingBusiness->status === 'pending' ? 'en revisión' : 'aprobado';
        return redirect()->back()
            ->with('error', "Ya tenés un negocio '{$data['name']}' ({$statusText}). No podés crear otro con el mismo nombre.")
            ->withInput();
    }

        // Generar slug para carpeta
        $slug = Str::slug($data['name']);
        
        // Procesar imagen principal
        if ($request->hasFile('main_image')) {
            $extension = $request->file('main_image')->extension();
            $path = $request->file('main_image')->storeAs(
                "businesses/{$slug}",
                "principal.{$extension}",
                'public'
            );
            $data['image'] = $path;
        } else {
            $data['image'] = null;
        }

        $data['user_id'] = Auth::id();
        $data['slug'] = $this->makeUniqueSlug($data['name']);
        
        // El negocio se crea como PENDIENTE (no visible al público)
        $data['status'] = 'pending';
        $data['published'] = false;

        // Crear el negocio
        $business = Business::create($data);

        // Procesar galería de imágenes extras
        $galleryImages = [];

        if ($request->hasFile('gallery_images')) {
            $index = 1;
            foreach ($request->file('gallery_images') as $file) {
                if ($file && $file->isValid()) {
                    $extension = $file->extension();
                    $path = $file->storeAs(
                        "businesses/{$slug}/galeria",
                        "{$index}.{$extension}",
                        'public'
                    );
                    $galleryImages[] = $path;
                    $index++;
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

        // Mensaje actualizado: avisa que está en revisión
        $message = '✅ ¡Tu negocio fue enviado para revisión! Un administrador lo verificará y lo publicará pronto.';
        if (count($galleryImages) > 0) {
            $message .= ' Se agregaron ' . count($galleryImages) . ' imágenes extras.';
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