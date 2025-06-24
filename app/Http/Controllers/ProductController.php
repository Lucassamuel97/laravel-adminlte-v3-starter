<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest; 
use App\Http\Requests\UpdateProductRequest; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Gate; 

class ProductController extends Controller
{
    public function __construct()
    {
        // Aplica o middleware de autenticação a todas as ações
        $this->middleware('auth');


        $this->authorizeResource(Product::class, 'product');
    }

    public function index()
    {
        $products = Product::paginate(10);
        return view('products.index', compact('products'));
    }


    public function create()
    {
        return view('products.create');
    }


    public function store(StoreProductRequest $request) 
    {
        // A validação é feita automaticamente pelo StoreProductRequest

        $productData = $request->validated();
        $productData['user_id'] = Auth::id();

        Product::create($productData);

        return redirect()->route('products.index')
                         ->with('success', 'Produto criado com sucesso!');
    }


    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }


    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }


    public function update(UpdateProductRequest $request, Product $product) 
    {

        $product->update($request->validated());

        return redirect()->route('products.index')
                         ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()->route('products.index')
                             ->with('success', 'Produto excluído com sucesso!');
        } catch (\Exception $e) {
            // Tratamento de erro mais robusto para exclusão (ex: se houver restrições de FK)
            return redirect()->route('products.index')
                             ->with('error', 'Erro ao excluir produto: ' . $e->getMessage());
        }
    }
}