<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash; 
use Yajra\DataTables\DataTables;// Para criptografar senhas

class UserController extends Controller
{
    public function __construct()
    {
        // Aplica o middleware de autenticação a todas as ações
        $this->middleware('auth');
        // Autoriza todas as ações via UserPolicy
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request)
    {
        // Verifica se a requisição é AJAX (feita pelo DataTables)
        if ($request->ajax()) {
            $data = User::select(['id', 'name', 'email', 'role'])->get(); 

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('role_badge', function($row){
                    $badgeClass = ($row->role == 'admin') ? 'badge-info' : 'badge-secondary';
                    return '<span class="badge ' . $badgeClass . '">' . ucfirst($row->role) . '</span>';
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="' . route('users.show', $row->id) . '" class="btn btn-info btn-xs" title="Ver Detalhes"><i class="fas fa-eye"></i></a> ';
                    $btn .= '<a href="' . route('users.edit', $row->id) . '" class="btn btn-warning btn-xs" title="Editar Usuário"><i class="fas fa-edit"></i></a> ';

                    if (auth()->user()->can('delete', $row)) {
                        $btn .= ' <form action="' . route('users.destroy', $row->id) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.\');">';
                        $btn .= '    ' . csrf_field() . method_field('DELETE'); // Adiciona os campos CSRF e METHOD para formulário DELETE
                        $btn .= '    <button type="submit" class="btn btn-danger btn-xs" title="Excluir Usuário"><i class="fas fa-trash"></i></button>';
                        $btn .= ' </form>';
                    } else {
                         $btn .= ' <button type="button" class="btn btn-danger btn-xs" disabled title="Você não tem permissão para excluir este usuário ou não pode excluir a si mesmo."><i class="fas fa-ban"></i></button>';
                    }

                    return $btn;
                })
                ->rawColumns(['role_badge', 'action'])
                ->make(true);
        }

        // Se a requisição não for AJAX, apenas retorna a view Blade.
        // O DataTables fará a requisição AJAX para preencher a tabela.
        return view('users.index');
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']); // Criptografa a senha

        User::create($data);

        return redirect()->route('users.index')
            ->with('success', 'Usuário criado com sucesso!');
    }


    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }


    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }


    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')
                ->with('success', 'Usuário excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Erro ao excluir usuário: ' . $e->getMessage());
        }
    }
}