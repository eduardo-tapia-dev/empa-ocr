<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/xampp/EMPA/public');
}
require_once __DIR__ . '/plantillas/header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Escanear EMPA</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<!-- Logo superior -->
<div class="logo-superior">
    <img src="<?= BASE_URL ?>/../img/minsal.png" alt="Logo MINSAL" class="logo">
</div>

<!-- Contenido principal -->
<main>
    <h2 class="titulo">Selecciona el método de escaneo</h2>

    <div class="menu-options">
        <!-- Botón escanear PDF -->
        <a href="<?= BASE_URL ?>/../modulo_lectura/Lectura_EMPA.php" class="option">
            <i class="fas fa-file-pdf fa-3x icon-blue"></i>
            <p>Escanear Archivo</p>
        </a>

        <!-- Botón escanear con cámara -->
        <a href="<?= BASE_URL ?>/../modulo_lectura/Lectura_EMPA_camara.php" class="option">
            <i class="fas fa-camera fa-3x icon-blue"></i>
            <p>Escanear con cámara</p>
        </a>

        <!-- Botón volver con estilo azul -->
        <a href="<?= BASE_URL ?>/index.php?url=menu" class="option">
            <i class="fas fa-arrow-left fa-3x icon-blue"></i>
            <p>Volver al menú principal</p>
        </a>
    </div>
</main>

</body>
</html>
