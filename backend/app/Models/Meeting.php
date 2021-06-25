<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        $new_date = date("Y-m-d", strtotime($data['date']));
        $new_start = date("H:i", strtotime($data['start']));
        $new_end = date("H:i", strtotime($data['end']));
        // FORMULAR A DATA E OS HORÁRIOS - TIMESTAMP
        $new_start = date("Y-m-d H:i:s", strtotime($new_date." ".$new_start));
        $new_end = date("Y-m-d H:i:s", strtotime($new_date." ".$new_end));
        // VALIDAR HORÁRIO DA REUNIÃO - PEGAR REUNIÕES ACEITAS
        $meetings = Meeting::where('id', '!=', $id)->where('status', true)->get();
        // VARIAVEL DE RETORNO
        $validate = true;
        foreach($meetings as $meeting){
            // PEGAR DATAS E HORÁRIO
            $date = date("Y-m-d", strtotime($meeting->date));
            $start = date("H:i", strtotime($meeting->start));
            $end = date("H:i", strtotime($meeting->end));
            // FORMULAR HORÁRIO INICIAL
            $start = date("Y-m-d H:i:s", strtotime($date." ".$start));
            // FORMULAR HORÁRIO FINAL
            $end = date("Y-m-d H:i:s", strtotime($date." ".$end));
            // VALIDAR SE AS DUAS REUNIÕES NÃO CONVERGEM
            if( ($new_start >= $start && $new_start < $end) || ($new_end > $start && $new_start < $end) ){
                $validate = false;
            }
        }

        return $validate;
    }

}
