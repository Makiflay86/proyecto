<x-app-layout>
    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">Nuevo Producto</h2>
                
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="nombre" placeholder="Nombre" class="w-full border p-2 rounded mb-4" required>
                    <input type="number" step="0.01" name="precio" placeholder="Precio" class="w-full border p-2 rounded mb-4" required>
                    <textarea name="descripcion" placeholder="Descripción" class="w-full border p-2 rounded mb-4"></textarea>
                    <label>Categoría</label>
                    <select name="categoria" class="w-full border p-2 rounded mb-4" required>
                        <option value="electronica">Electrónica</option>
                        <option value="hogar">Hogar</option>
                        <option value="ropa">Ropa</option>
                    </select>
                    <input type="file" name="images[]" multiple class="w-full mb-4">
                    
                    <button class="bg-blue-600 text-white px-4 py-2 rounded">Guardar Producto</button>
                    <a href="{{ route('products.index') }}" class="ml-4 text-gray-500">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>