<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validación estricta: ¡No confíes nunca en el usuario!
        $validatedData = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio'      => 'required|numeric|min:0',
            'categoria'   => 'required|string',
            'images.*'    => 'image|mimes:jpeg,png,jpg|max:2048' // Valida cada imagen
        ]);

        // 2. Transacción de Base de Datos
        // Si algo falla al guardar una imagen, no queremos un producto "huérfano" en la DB
        DB::transaction(function () use ($request, $validatedData) {
            
            $product = Product::create($validatedData);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('productos', 'public');
                    
                    $product->images()->create(['path' => $path]);
                }
            }
        });

        return back()->with('success', 'Producto creado correctamente.');
    }

    public function index()
    {
        // 'with' carga las imágenes para no hacer mil consultas a la vez
        $products = Product::with('images')->latest()->get(); 
        return view('products.index', compact('products'));
    }

    public function create() {
        return view('products.create');
    }
}
