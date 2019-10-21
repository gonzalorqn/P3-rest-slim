<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once './vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

/*
�La primera l�nea es la m�s importante! A su vez en el modo de 
desarrollo para obtener informaci�n sobre los errores
 (sin �l, Slim por lo menos registrar los errores por lo que si est� utilizando
  el construido en PHP webserver, entonces usted ver� en la salida de la consola 
  que es �til).

  La segunda l�nea permite al servidor web establecer el encabezado Content-Length, 
  lo que hace que Slim se comporte de manera m�s predecible.
*/

$app = new \Slim\App(["settings" => $config]);


$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("GET => Bienvenido!!! a SlimFramework");
    return $response;

});

$app->post('[/]', function (Request $request, Response $response) {    
  $response->getBody()->write("POST => Bienvenido!!! a SlimFramework");
  return $response;

});

$app->put('[/]', function (Request $request, Response $response) {    
  $response->getBody()->write("PUT => Bienvenido!!! a SlimFramework");
  return $response;

});

$app->delete('[/]', function (Request $request, Response $response) {    
  $response->getBody()->write("DELETE => Bienvenido!!! a SlimFramework");
  return $response;

});

$app->get('/parametros/{nombre}[/{apellido}]', function (Request $request, Response $response, $args) {    
  $nombre = $args['nombre'];
  if(isset($args['apellido']))
  {
    $response->getBody()->write("Bienvenido " . $nombre . " " . $args['apellido']);
  }
  else
  {
    $response->getBody()->write("Bienvenido " . $nombre);
  }
  return $response;

});

$app->group('/json', function () {    
  $this->post('[/]', function (Request $request, Response $response) { 
    $titulo = date("Ymd_His");
    var_dump($request->getParsedBody());
    $archivos = $request->getUploadedFiles();
    $destino="./fotos/";
    $nombreAnterior=$archivos['foto']->getClientFilename();
    $extension= explode(".", $nombreAnterior);
    $extension=array_reverse($extension);

    $archivos['foto']->moveTo($destino.$titulo.".".$extension[0]);
    $response->getBody()->write("Bienvenido ");
    return $response;
  
  });
  $this->get('/{nombre}/{apellido}', function (Request $request, Response $response, $args) { 
    $obj = new stdclass();
    //var_dump($args);
    $obj->nombre = $args['nombre'];
    $obj->apellido = $args['apellido'];
    //var_dump($obj);
    $retorno = $response->withJson($obj, 200);
    return $retorno;
  
  });
  $this->put('[/]', function (Request $request, Response $response) { 
    $ArrayDeParametros = $request->getParsedBody();
    $json = json_decode($ArrayDeParametros["cadenaJson"]);
    $obj->nombre = $json->apellido;
    $obj->apellido = $json->nombre;
    $retorno = $response->withJson($obj, 200);
    return $retorno;
  
  });

});

$app->run();