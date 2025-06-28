<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{

    public function index()
    {
        return view('events.calendar');
    }

    public function getEvents(Request $request)
    {
        // filtrar por usuário logado
        $events = Auth::user()->events; // Apenas eventos do usuário logado

        // todos os eventos (se o usuário tiver permissão para ver)
        // $events = Event::all();

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'color' => 'nullable|string',
        ]);

        $event = Auth::user()->events()->create([
            'title' => $request->title,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
            'color' => $request->color,
        ]);

        return response()->json($event);
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event); // Certifique-se que o usuário pode atualizar o evento

        $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'color' => 'nullable|string',
        ]);

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
            'color' => $request->color,
        ]);

        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event); // Certifique-se que o usuário pode deletar o evento
        $event->delete();
        return response()->json(['message' => 'Evento excluído com sucesso!']);
    }
}