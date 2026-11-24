<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\UsuarioController;
use App\Controllers\ExamenController;
use App\Controllers\EmpaController;
use App\Controllers\MetricaController;

// ✅ Definir BASE_URL para uso en vistas y redirecciones
if (!defined('BASE_URL')) {
    define('BASE_URL', '/xampp/EMPA/public');
}

// Conexión PDO global
try {
    $conexion = new PDO('mysql:host=localhost;dbname=bd_empa_2;charset=utf8', 'root', '');
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Enrutamiento principal
$url = $_GET['url'] ?? 'login';
$urlParts = explode('/', $url);
$controllerName = $urlParts[0];
$action = $urlParts[1] ?? 'index';

switch ($controllerName) {
    case 'login':
        $controller = new AuthController();
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $controller->loginUsuario()
            : $controller->mostrarLogin();
        break;

    case 'registro':
        $controller = new AuthController();
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $controller->registrarUsuario()
            : $controller->mostrarRegistro();
        break;

    case 'menu':
        (new AuthController())->mostrarMenu();
        break;

    case 'menu_usuario':
        (new AuthController())->mostrarMenuUsuario();
        break;

    case 'menu_escanear':
        require_once dirname(__DIR__) . '/app/views/menu_escanear.php';
        break;

    case 'cerrar_sesion':
        (new AuthController())->cerrarSesion();
        break;

    case 'lectura':
        (new EmpaController($conexion))->procesarFormulario();
        break;

    case 'usuario':
        $controller = new UsuarioController($conexion);
        match ($action) {
            'index' => $controller->index(),
            'crear' => $_SERVER['REQUEST_METHOD'] === 'POST' ? $controller->guardar() : $controller->crear(),
            'guardar' => $controller->guardar(),
            'editar' => isset($_GET['rut']) ? $controller->editar($_GET['rut']) : exit("Falta parámetro RUT"),
            'actualizar' => $controller->actualizar(),
            'eliminar' => isset($_GET['rut']) ? $controller->eliminar($_GET['rut']) : exit("Falta parámetro RUT"),
            default => exit("Acción no válida en usuario: $action"),
        };
        break;

    case 'examen':
        $controller = new ExamenController($conexion);
        match ($action) {
            'index' => $controller->index(),
            'editar' => isset($_GET['id']) ? $controller->editar($_GET['id']) : exit("Falta parámetro ID"),
            'actualizar' => $controller->actualizar(),
            'eliminar' => isset($_GET['id']) ? $controller->eliminar($_GET['id']) : exit("Falta parámetro ID"),
            default => exit("Acción no válida en examen: $action"),
        };
        break;

    case 'metrica':
        $controller = new MetricaController($conexion);
        $action === 'index'
            ? $controller->index()
            : exit("Acción no válida en metrica: $action");
        break;

    default:
        http_response_code(404);
        echo "Página no encontrada: $url";
        break;
}
