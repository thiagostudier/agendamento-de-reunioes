import { api } from '@/services/api.ts';

export default function log({ next, to }) {
    // PEGAR O USUÁRIO
    const auth = JSON.parse(localStorage.getItem('user'));
    // VERIFICAR SE HÁ UM USUÁRIO LOGADO
    if(auth != null){
        api.post(`get-me`, {}, {headers: {"Authorization":"Bearer "+auth.token}},)
        .then(response => {
            if(response){
                return next('/dashboard');
            }else{
                return next();
            }
        })
        .catch(e => {
            return next();
        })
    }else{
        return next();
    }
}