# Escopo e objetivo

Desafio técnico Full Stack.

Sistema de agendamento de reuniões. Permite cadastrar reuniões, aprovar, listar, exibir em um calendário, editar e remover.

O projeto inclui:

(a) Laravel

(b) VueJs

## Baixar, instalar e rodar aplicação:

- `git clone https://github.com/thiagostudier/agendamento-de-reunioes`
- `cd agendamento-de-reunioes`

#### Backend

- `cd backend`
- `composer install`

> _Obs: Criar arquivo ".env" e configurar variáveis de ambiente (DATABASE e MAIL)

- `php artisan key:generate` - criar chave da aplicação

- `php artisan migrate`
- `php artisan db:seed`
- `php artisan serve`

O Serviço rodará na porta 8000: localhost:8000/

Usuário e senha do seed: 

- `jorge@piperun.com`
- `password`

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
$ php artisan make:notification NewMeetingNotification
$ php artisan vendor:publish --tag=laravel-notifications

//CRIAR SEEDERS
$ php artisan make:seeder UserSeeder
$ php artisan db:seed

```