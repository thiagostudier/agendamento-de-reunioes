<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Meeting;
use App\Http\Requests\MeetingRequest;
use App\Notifications\NewMeetingNotification;

class MeetingController extends Controller
{
    //LISTAR REUNIÕES
    public function index(){
        // PEGAR AS REUNIÕES
        $meetings = Meeting::orderBy('date', 'asc')->orderBy('start', 'asc')->all();
        // FORMULAR DADOS
        $meetings->map(function($meeting){
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
        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $subject = $data['subject'] ?? '';
        $date = $data['date'] ?? '';
        $start = $data['start'] ?? '';
        $end = $data['end'] ?? '';
        // FILTRAR REUNIÕES
        $meetings = Meeting::where('name', 'like', '%'.$name.'%')
        ->where('email', 'like', '%'.$email.'%')
        ->where('subject', 'like', '%'.$subject.'%')
        ->where('date', 'like', '%'.$date.'%')
        ->where('start', 'like', '%'.$start.'%')
        ->where('end', 'like', '%'.$end.'%');
        // POR DATA INICIAL
        if(isset($data['date_start'])){
            $meetings = $meetings->where('date', '>=', $data['date_start']);
        }
        // POR DATA FINAL
        if(isset($data['date_end'])){
            $meetings = $meetings->where('date', '<=', $data['date_end']);
        }
        // SE APENAS AS REUNIÕES ACEITAS
        if(isset($data['filteringMeetingsAccept']) && $data['filteringMeetingsAccept'] == true){
            $meetings = $meetings->where('status', true);
        }
        // SE APENAS AS REUNIÕES NOVAS
        if(isset($data['filteringNewMeetings']) && $data['filteringNewMeetings'] == true){
            $meetings = $meetings->where('new', true);
        }
        // SE APENAS AS REUNIÕES FUTURAS
        if(isset($data['futureMeetings']) && $data['futureMeetings'] == true){
            $date = date("Y-m-d");
            $meetings = $meetings->where('date','>=',$date);
        }
        // PEGAR OS DADOS
        $meetings = $meetings->orderBy('date', 'asc')->orderBy('start', 'asc')->get();
        // FORMULAR DADOS
        $meetings->map(function($meeting){
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
        // PEGAR DIA DA SEMANA
        $data['week'] = date("D", strtotime($data['date']));
        // VALIDAR CAMPOS
        $validation = Validator::make($data, [
            'name' => 'required|string|max:255|min:1',
            'email' => 'required|email|string|max:255|min:1',
            'subject' => 'required|string|max:255|min:1',
            'date' => 'required|date|date_format:Y-m-d',
            'start' => 'required|date_format:H:i|after_or_equal:08:00|before_or_equal:18:00',
            'end' => 'required|date_format:H:i|after_or_equal:08:00|before_or_equal:18:00',
            'week' => 'required|in:Mon,Tue,Wed,Thu,Fri',
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
    public function update(Request $request, $id){
        // PEGAR REUNIÃO
        $meeting = Meeting::findOrFail($id);
        // PEGAR OS DADOS
        $data = $request->all();
        // PEGAR DIA DA SEMANA
        $data['week'] = date("D", strtotime($data['date']));
        // VALIDAR CAMPOS
        $validation = Validator::make($data, [
            'name' => 'required|string|max:255|min:1',
            'email' => 'required|email|string|max:255|min:1',
            'subject' => 'required|string|max:255|min:1',
            'date' => 'required|date|date_format:Y-m-d',
            'start' => 'required|date_format:H:i|after_or_equal:08:00|before_or_equal:18:00',
            'end' => 'required|date_format:H:i|after_or_equal:08:00|before_or_equal:18:00',
            'status' => 'boolean',
            'new' => 'boolean',
            'week' => 'required|in:Mon,Tue,Wed,Thu,Fri',
        ]);
        // SE HOUVER ERROS, RETORNAR PARA O USUÁRIO
        if($validation->fails()){
            return ['errors' => $validation->errors()];
        }
        // VALIDAR HORÁRIOS DA REUNIÃO
        if($meeting->status == true){
            $validation = Meeting::validateDates($id, $data);
            if(!$validation){
                return ['errors' => ['date'=>['Há uma reunião marcada no mesmo horário.']]];
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
