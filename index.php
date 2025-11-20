<?php

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

require __DIR__ . '/config/config.php';
require __DIR__ . '/app/core/Database.php';
require __DIR__ . '/app/core/Model.php';
require __DIR__ . '/app/core/Controller.php';

foreach (glob(__DIR__ . '/app/controllers/*.php') as $file) {
	require_once $file;
}
foreach (glob(__DIR__ . '/app/models/*.php') as $file) {
	require_once $file;
}

$url = $_GET['url'] ?? 'home/index';
$parts = explode('/', trim($url, '/'));
$controllerName = ucfirst($parts[0]) . 'Controller';
$action = $parts[1] ?? 'index';

if (!class_exists($controllerName)) {
	header("HTTP/1.0 404 Not Found");
	echo "Controller not found: $controllerName";
	exit;
}

$controller = new $controllerName();
if (!method_exists($controller, $action)) {
	header("HTTP/1.0 404 Not Found");
	echo "Action not found: $action";
	exit;
}

$controller->$action();
