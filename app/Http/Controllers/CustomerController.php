<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Customer::class, 'customer');
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::select(['id', 'nome', 'email', 'telefone', 'cpf']);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if (auth()->user()->can('view', $row)) {
                        $btn .= '<a href="' . route('customers.show', $row->id) . '" class="btn btn-info btn-xs" title="Ver Detalhes"><i class="fas fa-eye"></i></a> ';
                    }
                    if (auth()->user()->can('update', $row)) {
                        $btn .= '<a href="' . route('customers.edit', $row->id) . '" class="btn btn-warning btn-xs" title="Editar Cliente"><i class="fas fa-edit"></i></a> ';
                    }
                    if (auth()->user()->can('delete', $row)) {
                        $btn .= '<form action="' . route('customers.destroy', $row->id) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Tem certeza que deseja excluir este cliente? Esta ação não pode ser desfeita.\');">';
                        $btn .= csrf_field();
                        $btn .= method_field('DELETE');
                        $btn .= '<button type="submit" class="btn btn-danger btn-xs" title="Excluir Cliente"><i class="fas fa-trash"></i></button>';
                        $btn .= '</form>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('customers.index');
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required|email|unique:customers',
            'telefone' => 'required',
            'endereco' => 'required',
            'cpf' => 'required|unique:customers',
            'rg' => 'required|unique:customers',
            'data_nascimento' => 'required|date',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'Cliente criado com sucesso.');
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'telefone' => 'required',
            'endereco' => 'required',
            'cpf' => 'required|unique:customers,cpf,' . $customer->id,
            'rg' => 'required|unique:customers,rg,' . $customer->id,
            'data_nascimento' => 'required|date',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Cliente excluído com sucesso.');
    }
}
