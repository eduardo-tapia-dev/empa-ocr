<?php
namespace App\Controllers;

use App\Models\Usuario;

class UsuarioController {

    public function index() {
        global $conexion;
        $usuarioModel = new Usuario($conexion);

        // Filtros desde GET
        $rut = $_GET['rut'] ?? '';
        $nombre = $_GET['nombre'] ?? '';
        $apellido = $_GET['apellido'] ?? '';

        // Paginación
        $pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
        $limite = isset($_GET['limite']) ? (int)$_GET['limite'] : 10;
        $offset = ($pagina - 1) * $limite;

        // Datos desde el modelo (con JOIN y alias tipo_nombre)
        $totalRegistros = $usuarioModel->contarUsuariosFiltrados($rut, $nombre, $apellido);
        $totalPaginas = max(1, ceil($totalRegistros / $limite));
        $usuarios = $usuarioModel->obtenerUsuariosFiltrados($rut, $nombre, $apellido, $limite, $offset);

        require_once __DIR__ . '/../views/usuarios/index.php';
    }

    public function crear() {
        require_once __DIR__ . '/../views/usuarios/crear.php';
    }

    public function guardar() {
        global $conexion;
        $usuario = new Usuario($conexion);

        if ($usuario->existeRut($_POST['rut'])) {
            $error = 'rut_existente';
            require_once __DIR__ . '/../views/usuarios/crear.php';
            return;
        }

        if ($usuario->existeCorreo($_POST['correo'])) {
            $error = 'correo_existente';
            require_once __DIR__ . '/../views/usuarios/crear.php';
            return;
        }

        $resultado = $usuario->registrar(
            $_POST['rut'],
            $_POST['nombre'],
            $_POST['apellido'],
            $_POST['correo'],
            $_POST['fono'],
            $_POST['password'],
            $_POST['tipo_u']
        );

        if ($resultado) {
            header('Location: ' . BASE_URL . '/index.php?url=usuario/index&success=1');
            exit();
        } else {
            $error = 'error_general';
            require_once __DIR__ . '/../views/usuarios/crear.php';
        }
    }

    public function editar($rut) {
        global $conexion;
        $usuario = new Usuario($conexion);
        $datos = $usuario->obtenerPorRut($rut);
        require_once __DIR__ . '/../views/usuarios/editar.php';
    }

    public function actualizar() {
        global $conexion;
        $usuario = new Usuario($conexion);
        $usuario->actualizar($_POST);
        header('Location: ' . BASE_URL . '/index.php?url=usuario/index&updated=1');
        exit();
    }

    public function eliminar($rut) {
        global $conexion;
        $usuario = new Usuario($conexion);
        $usuario->eliminar($rut);
        header('Location: ' . BASE_URL . '/index.php?url=usuario/index');
    }
}
