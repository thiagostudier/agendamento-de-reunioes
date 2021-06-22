<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Meeting;
use App\Http\Requests\MeetingRequest;

class MeetingController extends Controller
{
    //LISTAR REUNIÕES
    public function index(){
        // PEGAR AS REUNIÕES
        $meetings = Meeting::all();
        // FORMULAR DADOS
        $meetings->map(function($meeting){
            // $meeting->title = $meeting->name.' | '.$meeting->subject;
            // $meeting->start = $meeting->date.'T'.$meeting->start;
            // $meeting->end = $meeting->date.'T'.$meeting->end;
            // $meeting->description = 'Lecture';
            $meeting->date = date("d/m/Y", strtotime($meeting->date));
            $meeting->start = date("H:i", strtotime($meeting->start));
            $meeting->end = date("H:i", strtotime($meeting->end));
        });
        return $meetings;
    }

    //PEGAR REUNIÃO
    public function show($id){
        // PEGAR REUNIÃO
        $meetings = Meeting::findOrFail($id);
        return $meetings;
    }
    
    //FILTRAR REUNIÕES
    public function filter(Request $request){
        // PEGAR AS REUNIÕES
        $data = $request->all();
        // FILTRAR REUNIÕES
        $meetings = Meeting::where('name', 'like', '%'.$data['name'].'%')
        ->where('email', 'like', '%'.$data['email'].'%')
        ->where('subject', 'like', '%'.$data['subject'].'%')
        ->where('date', 'like', '%'.$data['date'].'%')
        ->where('start', 'like', '%'.$data['start'].'%')
        ->where('end', 'like', '%'.$data['end'].'%');
        // SE APENAS AS REUNIÕES NOVAS
        if(isset($data['filteringNewMeetings'])){
            $meetings = $meetings->where('new', true);
        }
        // SE APENAS AS REUNIÕES FUTURAS
        if(isset($data['futureMeetings'])){
            $date = date("Y-m-d");
            $meetings = $meetings->where('date','>=',$date);
        }
        // PEGAR OS DADOS
        $meetings = $meetings->get();
        // FORMULAR DADOS
        $meetings->map(function($meeting){
            // $meeting->title = $meeting->name.' | '.$meeting->subject;
            // $meeting->start = $meeting->date.'T'.$meeting->start;
            // $meeting->end = $meeting->date.'T'.$meeting->end;
            // $meeting->description = 'Lecture';
            $meeting->date = date("d/m/Y", strtotime($meeting->date));
            $meeting->start = date("H:i", strtotime($meeting->start));
            $meeting->end = date("H:i", strtotime($meeting->end));
        });

        return $meetings;
    }

    //CADASTRAR REUNIÃO
    public function store(Request $request){
        // PEGAR OS DADOS
        $data = $request->all();
        // VALIDAR CAMPOS
        $validation = Validator::make($data, [
            'name' => 'required|string|max:255|min:1',
            'email' => 'required|email|string|max:255|min:1',
            'subject' => 'required|string|max:255|min:1',
            'date' => 'required|date|date_format:Y-m-d',
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i',
        ]);
        // SE HOUVER ERROS, RETORNAR PARA O USUÁRIO
        if($validation->fails()){
            return ['errors' => $validation->errors()];
        }
        // CADASTRAR REUNIÃO
        $meeting = Meeting::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'],
            'date' => $data['date'],
            'start' => $data['start'],
            'end' => $data['end'],
            'status' => 0,
            'new' => 1,
        ]);
        // RETORNAR REUNIÃO NOVA
        return $meeting;
    }

    //ATUALIZAR REUNIÃO
    public function update(Request $request, $id){
        // PEGAR OS DADOS
        $data = $request->all();
        // VALIDAR CAMPOS
        $validation = Validator::make($data, [
            'name' => 'required|string|max:255|min:1',
            'email' => 'required|email|string|max:255|min:1',
            'subject' => 'required|string|max:255|min:1',
            'date' => 'required|date|date_format:Y-m-d',
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i',
            'status' => 'boolean',
            'new' => 'boolean',
        ]);
        // SE HOUVER ERROS, RETORNAR PARA O USUÁRIO
        if($validation->fails()){
            return ['errors' => $validation->errors()];
        }
        // PEGAR REUNIÃO
        $meeting = Meeting::findOrFail($id);
        // ATUALIZAR REUNIÃO
        $meeting->update($data);
        // RETORNAR REUNIÃO NOVA
        return $meeting;
    }

    //ATUALIZAR STATUS
    public function accept(Request $request, $id){
        // PEGAR OS DADOS
        $data = $request->all();
        // VALIDAR CAMPOS
        $validation = Validator::make($data, [
            'status' => 'boolean',
        ]);
        // SE HOUVER ERROS, RETORNAR PARA O USUÁRIO
        if($validation->fails()){
            return ['errors' => $validation->errors()];
        }
        // PEGAR REUNIÃO
        $meeting = Meeting::findOrFail($id);
        // ATUALIZAR REUNIÃO
        $meeting->update([
            'status' => $data['status'],
            'new' => 0
        ]);
        // RETORNAR REUNIÃO NOVA
        return $meeting;
    }

}
