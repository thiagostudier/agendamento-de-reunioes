import Vue from 'vue'
import { api } from '@/services/api.ts';
import store from '@/store';

export default function user() {
    // PEGAR TOKEN
    var token = store.state.token || false;
    // VERIFICAR SE HÁ UM USUÁRIO LOGADO
    if(token){
        api.post(`get-me`, {}, {
            headers: {
                "Authorization":"Bearer "+token
            }
        })
        .then(response => {
            return true;
        })
        .catch(e => {
            return false;
        })
    }else{
        return false;
    }
}