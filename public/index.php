<?php

ini_set('display_errors', 1);
ini_set('display_startup', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';
session_start();

if (file_exists("../.env")) {
	$dotenv = new Dotenv\Dotenv(__DIR__ .'/..');
	$dotenv->load();
}

use Aura\Router\RouterContainer;
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
		'driver'    => getenv('DB_DRIVER'),
		'host'      => getenv('DB_HOST'),
		'database'  => getenv('DB_NAME'),
		'username'  => getenv('DB_USER'),
		'password'  => getenv('DB_PASS'),
		'charset'   => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix'    => '',
	]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$contenedorDeRutas = new RouterContainer();

$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
	$_SERVER,
	$_GET,
	$_POST,
	$_COOKIE,
	$_FILES
);

$mapa = $contenedorDeRutas->getMap();

$mapa->get(
	'home',
	'/sistema-de-asistencia-grupo-5/',
	['controller'=>'App\controllers\HomeController','action'=>'getHome']
);

//ruta para login de administradores//
$mapa->get(
	'login',
	'/sistema-de-asistencia-grupo-5/login',
	['controller'=>'App\controllers\LoginController','action'=>'getSesion']
);


$mapa->post(
	'verificar',
	'/sistema-de-asistencia-grupo-5/login',
	['controller'=>'App\controllers\LoginController','action'=>'verificar']
);
///---------------------------------//

$mapa->get(
	'formulario_inscripcion',
	'/sistema-de-asistencia-grupo-5/inscripcion',
	['controller'=>'App\controllers\InscripcionController','action'=>'getForminscripcion']
);

//ruta de tipo post para el carga del formulario en la base de datos
$mapa->post('formulario_inscripcion_store',
		 '/sistema-de-asistencia-grupo-5/inscripcion_realizada',
		 ['controller'=>'App\controllers\InscripcionController',
		 'action'=>'storeForminscripcion']);
//------Mach whit route-------------
$matcher = $contenedorDeRutas->getMatcher();

$route = $matcher->match($request);
//------Mach whit route-------------

if (!$route) {
	echo 'no encuentro esa ruta';
} else {

	$capturadorDeDatos = $route->handler;

	$nombreControlador = $capturadorDeDatos['controller'];
	$nombreDeFuncion   = $capturadorDeDatos['action'];
	$Autentificacion   = $capturadorDeDatos['auth']??false;

	$log = $_SESSION['login'][2]??null;

	if ($Autentificacion && !$log) {
		$controlador     = new App\controllers\loginController;
		$nombreDeFuncion = 'getLogin';
		$response        = $controlador->$nombreDeFuncion($request);
	} else {

		$controlador = new $nombreControlador;
		$response    = $controlador->$nombreDeFuncion($request);

	}

	echo $response->getBody();

}

