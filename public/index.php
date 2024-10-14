<?php

use \Psr\Http\Message\ServerRequestInterface as Request; //namespace de requisição
use \Psr\Http\Message\ResponseInterface as Response; //namespace de resposta
use Illuminate\Database\Capsule\Manager as Capsule; //namespace do illuminate database

require '../vendor/autoload.php'; //autoload do composer

$app = new \Slim\App([
  'settings' => ['displayErrorDetails' => true]
]); //Instancia um objeto da API
/* 

Utilização das rotas com Slim Framework


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
//Serviços e Dependencias

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

/*------------------------------------------------------------------------------------*/
//Tipos de Resposta
//Cabeçalho, texto, JSON, XML
/*Cabeçalho*/
$app->get('/header', function (Request $request, Response $response) {
  $response->getBody()->write('Esse é um retorno header');
  return $response->withHeader('allow', 'PUT')
    ->withAddedHeader('Content-Length', 30);
});

/*JSON*/
$app->get('/json', function (Request $request, Response $response) {
  return $response->withJson([
    "nome" => "Lucas Kalks",
    "email" => "lucas@kalks.com"
  ]);
});

/*XML*/
$app->get('/xml', function (Request $request, Response $response) {
  $xml = file_get_contents('arquivo.xml');
  $response->getBody()->write($xml);

  return $response->withHeader('Content-Type', 'application/xml');
});

/*Middleware*/
$app->add(function ($request, $response, $next) {
  $response->write('Inicio camada 1 + ');
  //return $next($request, $response);
  $response = $next($request, $response);
  $response->write(' + Fim camada 1');
  return $response;
});

$app->add(function ($request, $response, $next) {
  $response->write('Inicio camada 2 + ');
  //return $next($request, $response);
  $response = $next($request, $response);
  $response->write(' + Fim camada 2');
  return $response;
});
/*
$app->add(function ($request, $response, $next) {
  $response->write('Inicio camada 2 + ');
  return $next($request, $response);
});
*/
$app->get('/users', function (Request $request, Response $response) {
  $response->getBody()->write('Ação principal Users');
});

$app->get('/posts', function (Request $request, Response $response) {
  $response->getBody()->write('Ação principal Posts');
});



/*-------------------------------------------------------------------------------------*/
/*
  Respostas de banco de dados
*/
$container = $app->getContainer();
$container['db'] = function () {
  $capsule = new Capsule;

  $capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'slim',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
  ]);

  // Make this Capsule instance available globally via static methods... (optional)
  $capsule->setAsGlobal();

  // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
  $capsule->bootEloquent();

  return $capsule;
};

$app->get('/pessoas', function (Request $request, Response $response) {
  $db = $this->get('db');
  /* CRIANDO TABELAS NO BANCO */

  /*
  $db->schema()->dropIfExists('usuarios');

  $db->schema()->create('usuarios', function ($table) {
    $table->increments('id');
    $table->string('nome');
    $table->string('email')->unique();
    $table->timestamps();
  });
  */

  /*INSERIR DADOS NO BANCO*/
  /*
  $db->table('usuarios')->insert([
    
    'nome' => 'Lucas Kalks',
    'email' => 'lucas@kalks.com'
    
    'nome' => 'Rafaela Ferreira',
    'email' => 'rafaela@ferreira.br'
  ]);
  */

  /*ATUALIZAR DADOS DO BANCO*/
  /*
  $db->table('usuarios')
    ->where('id', 1)
    ->update([
      'nome' => 'Lucas Chagas'
    ]);
    */

  /*DELETAR DADOS DO BANCO*/
  /*
  $db->table('usuarios')
    ->where('id', 1)
    ->delete();
    */

  /*LISTAR DADOS DO BANCO*/
  $tabela_usuarios = $db->table('usuarios')->get();
  foreach ($tabela_usuarios as $usuario) {
    echo "<br> $usuario->nome <br>";
  }
});

$app->run();//executa o framework
