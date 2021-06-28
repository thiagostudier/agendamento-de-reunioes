<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'date',
        'start',
        'end',
        'status',
        'new',
        'created_at',
        'updated_at',
        'date_format'
    ];

    protected $casts = [
        'date'=>'datetime:d/m/Y',
        'start'=>'datetime:H:i',
        'end'=>'datetime:H:i'
    ];

    public static function validateDates($id, $data){
        // FORMULAR A DATA E OS HORÁRIOS
        $new_date = Carbon::parse($data['date'])->format('Y-m-d');
        $new_start = Carbon::parse($data['start'])->format('H:i');
        $new_end = Carbon::parse($data['end'])->format('H:i');
        // FORMULAR A DATA E OS HORÁRIOS - TIMESTAMP
        $new_start = Carbon::parse($new_date." ".$new_start)->format("Y-m-d H:i:s");
        $new_end = Carbon::parse($new_date." ".$new_end)->format("Y-m-d H:i:s");
        // VALIDAR HORÁRIO DA REUNIÃO - PEGAR REUNIÕES ACEITAS
        $meetings = Meeting::where('id', '!=', $id)->where('status', true)->get();
        // VARIAVEL DE RETORNO
        $validate = true;
        foreach($meetings as $meeting){
            // PEGAR DATAS E HORÁRIO
            $date = Carbon::parse($meeting->date)->format('Y-m-d');
            $start = Carbon::parse($meeting->start)->format('H:i');
            $end = Carbon::parse($meeting->end)->format('H:i');
            // FORMULAR HORÁRIO INICIAL
            $start = Carbon::parse($date." ".$start)->format("Y-m-d H:i:s");
            // FORMULAR HORÁRIO FINAL
            $end = Carbon::parse($date." ".$end)->format("Y-m-d H:i:s");
            // VALIDAR SE AS DUAS REUNIÕES NÃO CONVERGEM
            if( ($new_start >= $start && $new_start < $end) || ($new_end > $start && $new_start < $end) ){
                $validate = false;
            }
        }

        return $validate;
    }

}
