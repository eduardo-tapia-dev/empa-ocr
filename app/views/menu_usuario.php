<?php
// Iniciar sesión solo si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificación de acceso para tipo de usuario 1 (encargado EMPA)
if (!isset($_SESSION['rut']) || (int)$_SESSION['tipo_u'] !== 1) {
    header("Location: /xampp/EMPA/public/index.php?url=login");
    exit();
}

// Definir constantes si no están definidas
if (!defined('ROOT_URL')) {
    define('ROOT_URL', '/xampp/EMPA'); // Ruta base del proyecto
}
if (!defined('BASE_URL')) {
    define('BASE_URL', '/xampp/EMPA/public'); // Ruta de carpeta pública
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Usuario</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <img src="<?= BASE_URL ?>/../img/minsal.png" alt="Ministerio de Salud" class="logo">
    </header>

    <main>
        <h2>Bienvenido, <?= isset($_SESSION['nombre'], $_SESSION['apellido']) 
            ? htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) 
            : 'Usuario' ?></h2>

        <div class="menu-options">
            <a href="<?= ROOT_URL ?>/modulo_lectura/Lectura_EMPA.php" class="option">
                <i class="fas fa-book fa-3x icon-blue"></i>
                <p>Scanear EMPA</p>
            </a>

            <a href="<?= BASE_URL ?>/index.php?url=metrica/index" class="option">
                <i class="fas fa-chart-bar fa-3x icon-blue"></i>
                <p>Métricas</p>
            </a>

            <a href="<?= BASE_URL ?>/index.php?url=examen/index" class="option">
                <i class="fas fa-file-medical fa-3x icon-blue"></i>
                <p>Ver Examen</p>
            </a>

            <a href="<?= BASE_URL ?>/index.php?url=cerrar_sesion" class="option logout">
                <i class="fas fa-sign-out-alt fa-3x"></i>
                <p>Cerrar Sesión</p>
            </a>
        </div>
    </main>
</body>
</html>
