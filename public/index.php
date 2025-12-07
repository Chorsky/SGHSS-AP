<?php
declare(strict_types=1);
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../src/Services/Router.php';
use App\Services\Router;

// 1. Captura a URI completa e o método da requisição
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// 2. Limpeza da URI: Remove o nome do script (index.php) e o caminho base.

// Encontra o caminho completo do script (ex: /SGHSS-API/public/index.php)
$scriptName = $_SERVER['SCRIPT_NAME'];

// Encontra o caminho relativo do script (ex: /index.php)
$basePath = dirname($scriptName);

// Remove o base path da URI (para o caso de subdiretórios)
$requestPath = str_replace($basePath, '', $uri);

// Remove a query string (ex: ?param=value)
$requestPath = strtok($requestPath, '?'); 

// 3. Chama o roteador com o caminho limpo
Router::handle($requestPath, $method);