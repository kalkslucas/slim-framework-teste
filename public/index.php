<?php

use \Psr\Http\Message\ServerRequestInterface as Request; //namespace de requisição
use \Psr\Http\Message\ResponseInterface as Response; //namespace de resposta

require '../vendor/autoload.php'; //autoload do composer

$app = new \Slim\App([
  'settings' => ['displayErrorDetails' => true]
]); //Instancia um objeto da API
/* 

utilização das rotas com GET


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
*/
/* Nomear rotas 
$app->get('/blog/postagens/{id}', function ($request, $response) {
  echo "Listar postagem para um ID ";
})->setName("blog");

$app->get('/meusite', function ($request, $response) {

  $retorno = $this->get("router")->pathFor("blog", ["id" => "10"]);

  echo $retorno;
});
*/

/*Agrupar rotas
$app->group('/v5', function () {

  $this->get('/usuarios', function () {
    echo "Listagem de usuarios";
  });

  $this->get('/postagens', function () {
    echo "Listagem de postagens";
  });
});
*/

/*PSR-7*/
$app->get('/postagem', function (Request $request, Response $response) {
  /*Escrever no corpo da resposta utilizando o padrão PSR-7*/
  $response->getBody()->write('Listagem de postagens!');
  return $response;
  //echo "Listagem de postagens";
});

/*
-----------------------------------------------------------------
Tipos de requisição
	• Os tipos de requisições HTTP são:
		○ GET -> Recuperar (semelhante ao SELECT)
		○ POST -> Inserir (semelhante ao INSERT)
		○ PUT -> Atualizar (semelhante ao UPDATE)
    ○ DELETE -> Deletar (semelhante ao DELETE)
*/

/*POST*/
$app->post('/usuarios/adiciona', function (Request $request, Response $response) {
  //Recupera o post
  $post = $request->getParsedBody();
  $nome = $post['nome'];
  $email = $post['email'];
  //Salvar no banco de dados com INSERT INTO...
  return $response->getBody()->write("$post[nome] - $post[email]");
});

/*PUT*/
$app->put('/usuarios/atualiza', function (Request $request, Response $response) {
  //Recupera o post
  $post = $request->getParsedBody();
  $id = $post['id'];
  $nome = $post['nome'];
  $email = $post['email'];
  //Atualizar dados com o UPDATE no banco!...
  return $response->getBody()->write("Sucesso ao atualizar o id: $id");
});

/*DELETE*/
$app->delete('/usuarios/remove/{id}', function (Request $request, Response $response) {
  //Recupera o post
  $id = $request->getAttribute('id');
  //Deletar dados do banco com o DELETE...
  return $response->getBody()->write("Sucesso ao remover o id: $id");
});

/*--------------------------------------------------------------*/


//Container dependency injection
/*Casos onde utiliza classes externas*/
class Servico {}

/* Container pimple */
$container = $app->getContainer();
$container['servico'] = function () {
  return new Servico;
};

$app->get('/servico', function (Request $req, Response $res) {
  $servico = $this->get('servico');
  var_dump($servico);
});


/* Container pimple com a classe criada no App*/
$container = $app->getContainer();
$container['View'] = function () {
  return new App\View;
};
/*Controllers como serviço*/
$app->get('/usuario', '\App\Controllers\Home:index');


/* Container pimple */
$container = $app->getContainer();
$container['Home'] = function () {
  return new App\Controllers\Home(new App\View);
};
/*Controllers como serviço*/
$app->get('/teste', 'Home:index');

$app->run();//executa o framework
