<?php

namespace App\Controllers;

class Home
{
  /*Criando um construtor que, no Slim, instancia o container de dependencia*/
  //Atributo criado para o container criado dentro do App Controller
  //protected $container;

  protected $view;
  public function __construct($view)
  {
    $this->view = $view;
  }

  public function index($request, $response)
  {
    // $view = $this->container->get('View');
    echo '<pre>';
    var_dump($this->view);
    echo '</pre>';
    return $response->write('Teste index');
  }
}
