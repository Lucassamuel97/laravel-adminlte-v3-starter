<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Contar produtos e usuários
        $totalProducts = Product::count();
        $totalUsers = User::count();

        // Passar os dados para a view
        return view('home', compact('totalProducts', 'totalUsers'));
    }
}
