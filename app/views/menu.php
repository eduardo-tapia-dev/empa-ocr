<?php
// Iniciar sesión solo si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Permitir acceso tanto a administradores (2) como encargados EMPA (1)
if (!isset($_SESSION['rut']) || !in_array((int)$_SESSION['tipo_u'], [1, 2])) {
    header("Location: /xampp/EMPA/public/index.php?url=login");
    exit();
}

// Definir rutas si no están definidas
if (!defined('ROOT_URL')) {
    define('ROOT_URL', '/xampp/EMPA'); // Ruta raíz del proyecto
}
if (!defined('BASE_URL')) {
    define('BASE_URL', '/xampp/EMPA/public'); // Carpeta pública
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <img src="<?= BASE_URL ?>/../img/minsal.png" alt="Ministerio de Salud" class="logo">
    </header>

    <main>
        <h2>Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) . ' ' . htmlspecialchars($_SESSION['apellido']) ?></h2>

        <div class="menu-options">
            <!-- Fila 1 -->
            <div class="menu-row">
                <a href="<?= BASE_URL ?>/../modulo_lectura/Lectura_EMPA.php" class="option">
                    <i class="fas fa-file-pdf fa-3x icon-blue"></i>
                    <p>Escanear Archivo</p>
                </a>

                <a href="<?= BASE_URL ?>/../modulo_lectura/Lectura_EMPA_camara.php" class="option">
                    <i class="fas fa-camera fa-3x icon-blue"></i>
                    <p>Escanear con cámara</p>
                </a>

                <a href="<?= BASE_URL ?>/index.php?url=metrica/index" class="option">
                    <i class="fas fa-chart-bar fa-3x icon-blue"></i>
                    <p>Métricas</p>
                </a>
            </div>

            <!-- Fila 2 -->
            <div class="menu-row">
                <a href="<?= BASE_URL ?>/index.php?url=examen/index" class="option">
                    <i class="fas fa-file-medical fa-3x icon-blue"></i>
                    <p>Ver Examen</p>
                </a>

                <?php if ((int)$_SESSION['tipo_u'] === 2): ?>
                    <a href="<?= BASE_URL ?>/index.php?url=usuario/index" class="option">
                        <i class="fas fa-user fa-3x icon-blue"></i>
                        <p>Usuarios</p>
                    </a>
                <?php endif; ?>

                <a href="<?= BASE_URL ?>/index.php?url=cerrar_sesion" class="option logout">
                    <i class="fas fa-sign-out-alt fa-3x"></i>
                    <p>Cerrar Sesión</p>
                </a>
            </div>
        </div>
    </main>
</body>
</html>
