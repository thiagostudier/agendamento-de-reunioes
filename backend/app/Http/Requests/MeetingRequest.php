<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeetingRequest extends FormRequest{

    protected function getValidatorInstance(){
        $data = $this->all();
        // PEGAR O DIA DA SEMANA QUE ESTÁ SENDO USADO NO CAMPO DATE SE EXISTIR
        $data['week'] = isset($data['date']) ? date("D", strtotime($data['date'])) : '';
        $this->getInputSource()->replace($data);
        return parent::getValidatorInstance();
    }

    public function authorize(){
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|min:1',
            'email' => 'required|email|string|max:255|min:1',
            'subject' => 'required|string|max:255|min:1',
            'date' => 'required|date|date_format:Y-m-d',
            'start' => 'required|date_format:H:i|after_or_equal:08:00|before_or_equal:18:00',
            'end' => 'required|date_format:H:i|after_or_equal:08:00|before_or_equal:18:00',
            'week' => 'required|in:Mon,Tue,Wed,Thu,Fri',
        ];
    }

    public function messages()
    {

        return [
            'required' => 'Este campo é obrigatório',
            'max' => 'O máximo de caracteres são 255',
            'min' => 'O mínimo de caracteres é 1',
            'string' => 'Formato inválido',
            'date' => 'Formato inválido',
            'date_format' => 'Formato inválido',
            'week.in' => 'As reuniões não podem ser marcadas aos finais de semana',
            'after_or_equal' => 'O horário deve ser a partir dás 08:00',
            'before_or_equal' => 'O horário deve ser até ás 18:00'
        ];
    }
}
