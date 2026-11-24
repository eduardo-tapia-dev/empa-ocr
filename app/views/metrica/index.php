<?php require_once __DIR__ . '/../plantillas/header.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Métricas del Examen EMPA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/../css/metrica.css">
</head>
<body>

    <!-- Encabezado con logo y título -->
    <div class="header-metrica">
        <img src="<?= BASE_URL ?>/../img/logo.png" alt="Logo MINSAL" class="logo-metrica">
        <h1 class="titulo-metrica">Métricas del Examen EMPA</h1>
    </div>

    <!-- Contenedor del informe Power BI -->
    <div class="metrica-contenedor">
        <div class="iframe-wrapper">
            <iframe 
                title="empa"
                width="100%" height="600"
                src=https://app.powerbi.com/view?r=eyJrIjoiZDQ1OGNhYTgtOWIwYy00Y2UxLWJkMGQtMjFhMmEzZjEwYjQxIiwidCI6IjZiMTY5NjM0LWZkY2EtNGM5Ny1hZDBmLWEyMjk0YzcxODdhNyIsImMiOjR9
                frameborder="0" allowFullScreen="true">
            </iframe>
        </div>

        <!-- Botón volver al menú -->
        <div class="botones-container">
            <a href="<?= BASE_URL ?>/index.php?url=menu" class="btn-volver">← Volver al Menú</a>
        </div>
    </div>

</body>
</html>
