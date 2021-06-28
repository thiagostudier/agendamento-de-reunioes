<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Meeting;
use App\Http\Requests\MeetingRequest;
use App\Notifications\NewMeetingNotification;
use App\Http\Resources\MeetingCollection;

class MeetingController extends Controller{

    //LISTAR REUNIÕES
    public function index(Request $request){
        // PEGAR AS REUNIÕES
        $data = $request->all();
        // DADOS DA CONSULTA
        $name = isset($data['name']) ? $data['name'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $subject = isset($data['subject']) ? $data['subject'] : '';
        $date = isset($data['date']) ? $data['date'] : '';
        $start = isset($data['start']) ? $data['start'] : '';
        $end = isset($data['end']) ? $data['end'] : '';
        $date_start = isset($data['date_start']) ? $data['date_start'] : false;
        $date_end = isset($data['date_end']) ? $data['date_end'] : false;
        $filteringMeetingsAccept = isset($data['filteringMeetingsAccept']) && $data['filteringMeetingsAccept'] == "true" ? true : false;
        $filteringNewMeetings = isset($data['filteringNewMeetings']) && $data['filteringNewMeetings'] == "true" ? true : false;
        $futureMeetings = isset($data['futureMeetings']) && $data['futureMeetings'] == "true" ? true : false;
        // FILTRAR REUNIÕES
        $meetings = Meeting::where('name', 'like', '%'.$name.'%')
        ->where('email', 'like', '%'.$email.'%')
        ->where('subject', 'like', '%'.$subject.'%')
        ->where('date', 'like', '%'.$date.'%')
        ->where('start', 'like', '%'.$start.'%')
        ->where('end', 'like', '%'.$end.'%')
        ->when($date_start, function ($query, $date_start) {
            return $query->where('date', '>=', $date_start);
        })->when($date_end, function ($query, $date_end) {
            return $query->where('date', '<=', $date_end);
        })->when($filteringMeetingsAccept, function ($query, $filteringMeetingsAccept) {
            return $query->where('status', true);
        })->when($filteringNewMeetings, function ($query, $filteringNewMeetings) {
            return $query->where('new', true);
        })->when($futureMeetings, function ($query, $futureMeetings) {
            return $query->where('date','>=',date("Y-m-d"));
        })
        ->orderBy('date', 'asc')->orderBy('start', 'asc')->get();

        return new MeetingCollection($meetings);
        
    }

    //PEGAR REUNIÃO
    public function show($id){
        // PEGAR REUNIÃO
        return Meeting::findOrFail($id);
    }
    
    //CADASTRAR REUNIÃO
    public function store(MeetingRequest $request){
        // PEGAR OS DADOS
        $data = $request->all();
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
        // ENVIAR E-MAILS
        try {
            // NOTIFICAR ADMIN
            $message = "<p>Nova reunião aguardando sua aprovação.</p><p>Agendada por: ".$meeting->name."<br />Data: ".date("d/m/Y", strtotime($meeting->date))." ".date("H:i", strtotime($meeting->start))." até ".date("H:i", strtotime($meeting->end))."<br />Pauta da reunião: ".$meeting->subject."</p>";
            \Notification::route('mail', 'thiago.studier9@gmail.com')->notify(new NewMeetingNotification($meeting->name, 'Piperun | Agendamento de reunião', $message));
            // NOTIFICAR USUÁRIO
            $message = "<p>Olá, ".$meeting->name."<br />Sua reunião foi agendada, aguardando aprovação da gerência.</p><p>Data: ".date("d/m/Y", strtotime($meeting->date))." ".date("H:i", strtotime($meeting->start))." até ".date("H:i", strtotime($meeting->end))."<br />Pauta da reunião: ".$meeting->subject."</p>";
            \Notification::route('mail', $meeting->email)->notify(new NewMeetingNotification($meeting->name, 'Piperun | Agendamento de reunião', $message));
        } catch (\Throwable $th) {
        }
        // RETORNAR REUNIÃO NOVA
        return $meeting;
    }

    //ATUALIZAR REUNIÃO
    public function update(MeetingRequest $request, $id){
        // PEGAR REUNIÃO
        $meeting = Meeting::findOrFail($id);
        // PEGAR OS DADOS
        $data = $request->all();
        // VALIDAR HORÁRIOS DA REUNIÃO
        if($meeting->status == true){
            $validation = Meeting::validateDates($id, $data);
            if(!$validation){
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'date' => ['Há uma reunião marcada no mesmo horário.'],
                ]);    
                throw $error;
            }
        }
        // ATUALIZAR REUNIÃO
        $meeting->update($data);
        // RETORNAR REUNIÃO NOVA
        return $meeting;
    }

    //ATUALIZAR STATUS
    public function accept(Request $request, $id){
        // PEGAR OS DADOS
        $data = $request->all();
        // PEGAR REUNIÃO
        $meeting = Meeting::findOrFail($id);
        // VALIDAR CAMPOS
        $validation = Validator::make($data, [
            'status' => 'boolean',
        ]);
        // SE HOUVER ERROS, RETORNAR PARA O USUÁRIO
        if($validation->fails()){
            return ['errors' => 'Houve algum erro ao realizar a ação'];
        }
        // VALIDAR HORÁRIOS DA REUNIÃO
        if($data['status'] == true){
            $validation = Meeting::validateDates($id, $meeting);
            if(!$validation){
                return ['errors' => 'Há uma reunião marcada no mesmo horário.'];
            }
        }
        // ATUALIZAR REUNIÃO
        $meeting->update([
            'status' => $data['status'],
            'new' => 0
        ]);
        // RETORNAR REUNIÃO NOVA
        return $meeting;
    }

    //ATUALIZAR REUNIÃO
    public function delete(Request $request, $id){
        // PEGAR REUNIÃO
        $meeting = Meeting::findOrFail($id);
        // REMOVER REUNIÃO
        $meeting->delete();
        // RETORNAR REUNIÃO NOVA
        return $meeting;
    }

}
