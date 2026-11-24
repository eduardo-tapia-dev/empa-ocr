<?php

namespace App\Controllers;

use App\Models\Usuario;

class AuthController
{
    private function iniciarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function mostrarLogin()
    {
        $this->iniciarSesion();
        require_once __DIR__ . '/../views/login.php';
    }

    public function loginUsuario()
    {
        $this->iniciarSesion();

        $rut = $_POST['rut'] ?? '';
        $clave = $_POST['password'] ?? '';

        $usuario = new Usuario($GLOBALS['conexion'] ?? null);
        $resultado = $usuario->verificarLogin($rut, $clave);

        if ($resultado) {
            $_SESSION['rut'] = $resultado['rut'];
            $_SESSION['tipo_u'] = (int) $resultado['tipo_u'];
            $_SESSION['nombre'] = $resultado['nombre'];
            $_SESSION['apellido'] = $resultado['apellido'];

            // Redirección según tipo de usuario
            if ($_SESSION['tipo_u'] === 1) {
                header("Location: " . BASE_URL . "/index.php?url=menu");
            } elseif ($_SESSION['tipo_u'] === 2) {
                header("Location: " . BASE_URL . "/index.php?url=menu");
            } else {
                session_destroy();
                echo "<script>
                        alert('Tipo de usuario no reconocido.');
                        window.location.href='" . BASE_URL . "/index.php?url=login';
                      </script>";
            }
            exit();
        } else {
            // Login fallido
            header("Location: " . BASE_URL . "/index.php?url=login&error=1");
            exit();
        }
    }

    public function mostrarRegistro()
    {
        require_once __DIR__ . '/../views/registrar.php';
    }

    public function registrarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rut = $_POST['rut'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $correo = $_POST['correo'];
            $fono = $_POST['fono'];
            $password = $_POST['password'];
            $tipo_u = $_POST['tipo_u'];

            $usuario = new Usuario($GLOBALS['conexion'] ?? null);
            $registrado = $usuario->registrar($rut, $nombre, $apellido, $correo, $fono, $password, $tipo_u);

            if ($registrado) {
                header("Location: " . BASE_URL . "/index.php?url=login&success=1");
                exit();
            } else {
                echo "<script>
                        alert('Error al registrar usuario');
                        window.location.href='" . BASE_URL . "/index.php?url=registro';
                      </script>";
            }
        }
    }

    public function mostrarMenu()
    {
        $this->iniciarSesion();

        if (!isset($_SESSION['rut']) || !in_array((int)$_SESSION['tipo_u'], [1, 2])) {
            header("Location: " . BASE_URL . "/index.php?url=login");
            exit();
        }

        require_once __DIR__ . '/../views/menu.php';
    }

    public function mostrarMenuUsuario()
    {
        $this->iniciarSesion();

        if (!isset($_SESSION['rut']) || (int)$_SESSION['tipo_u'] !== 1) {
            header("Location: " . BASE_URL . "/index.php?url=login");
            exit();
        }

        require_once __DIR__ . '/../views/menu_usuario.php';
    }

    public function cerrarSesion()
    {
        $this->iniciarSesion();
        session_destroy();
        header("Location: " . BASE_URL . "/index.php?url=login");
        exit();
    }
}
