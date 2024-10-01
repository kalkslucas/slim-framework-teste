<?php

use \Psr\Http\Message\ServerRequestInterface as Request; //namespace de requisição
use \Psr\Http\Message\ResponseInterface as Response; //namespace de resposta

require '../vendor/autoload.php'; //autoload do composer

$app = new \Slim\App; //Instancia um objeto da API
$app->get('/', function () { //pelo GET você recupera, dentro de pattern, você define a rota e, dentro da função anomina, exibe as informações na tela
  echo '!!Seja bem vindo ao projeto!!';
});
$app->get('/postagens2', function () { //pelo GET você recupera, dentro de pattern, você define a rota e, dentro da função anomina, exibe as informações na tela
  echo '!!Postagens!!';
});
$app->get('/usuarios[/{id}]', function ($request, $response) { //pelo GET você recupera, dentro de pattern, você define a rota e, dentro da função anomina, exibe as informações na tela
  $id = $request->getAttribute('id');
  //Recupera o id e exibe na tela
  echo "Listando usuarios ou Usuário: $id";
});
$app->get('/postagens[/{mes}[/{ano}]]', function ($request, $response) { //pelo GET você recupera, dentro de pattern, você define a rota e, dentro da função anomina, exibe as informações na tela
  $mes = $request->getAttribute('mes');
  $ano = $request->getAttribute('ano');
  //Recupera o id e exibe na tela
  echo "Postagens do mês: $mes e ano: $ano";
});
$app->get('/lista/{itens:.*}', function ($request, $response) { //pelo GET você recupera, dentro de pattern, você define a rota e, dentro da função anomina, exibe as informações na tela

  $itens = $request->getAttribute('itens');
  explode('/', $itens);
  //Recupera o id e exibe na tela
  echo "Listas: $itens";
});
$app->run();//executa o framework
