# Escopo e objetivo

Desafio técnico Full Stack.

O objetivo é desenvolver um sistema para agendamento de reuniões.

O projeto inclui:

(a) Laravel

(b) VueJs

## Baixar, instalar e rodar aplicação:

- `git clone https://github.com/thiagostudier/agendamento-de-reunioes`
- `cd agendamento-de-reunioes`

### Instalar pacotes do node e subir ambiente

> _Obs: Necessário ter npm instalado

#### Backend

- `cd backend`
- `composer install`

Configurar variáveis de ambiente (arquivo ".env" -> DATABASE e MAIL)

- `php artisan migrate` - Criar banco de dados
- `php artisan serve`

O Serviço rodará na porta 8000: localhost:8000/

#### Frontend

- `cd frontend`
- `npm install`
- `npm run dev`

O Serviço rodará na porta 8080: localhost:8080/

## Bibliotecas e comandos utilizados no desenvolvimento

#### Frontend

```
$ vue init webpack frontend //CRIAR PROJETO FRONTEND
$ npm i -S vue-the-mask //BIBLIOTECA DE INPUT MASK
$ npm i axios //PARA TRABALHAR COM A API BACKEND
$ npm i vue-notification //BIBLIOTECA DE NOTIFICAÇÕES JS
$ npm install --save vue-full-calendar //BIBLIOTECA DO FULLCALENDAR
```

#### Backend

```
$ composer global require laravel/installer //INSTALAR LARAVEL
$ laravel new backend //CRIAR PROJETO LARAVEL

//INSTALAR BIBLIOTECA DE AUTENTICAÇÃO PASSPORT
$ composer require laravel/passport 
$ php artisan passport:install 

//AUTENTICAÇÃO LARAVLE
$ composer require laravel/ui
$ php artisan ui vue --auth
$ npm install

//PARA ENVIO DE NOTIFICAÇÕES/EMAILS
php artisan make:notification NewMeetingNotification
php artisan vendor:publish --tag=laravel-notifications

```