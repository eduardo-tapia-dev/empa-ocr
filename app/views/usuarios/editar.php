<?php require_once __DIR__ . '/../plantillas/header.php'; ?>

<!-- Vincular al CSS -->
<link rel="stylesheet" href="<?= BASE_URL ?>/../css/editarusuario.css">

<!-- Logo -->
<div class="logo-superior">
    <img src="<?= BASE_URL ?>/../img/minsal.png" alt="Logo MINSAL" class="logo-minsal">
</div>

<!-- Contenedor centrado -->
<div class="usuarios-centro">
    <div class="formulario-wrapper">
        <h2 class="titulo">Editar Usuario</h2>

        <form action="<?= BASE_URL ?>/index.php?url=usuario/actualizar" method="POST" class="formulario-usuario">
            <!-- Rut solo lectura -->
            <input type="text" name="rut" value="<?= $datos['rut'] ?>" readonly>

            <input type="text" name="nombre" value="<?= $datos['nombre'] ?>" required placeholder="Nombre">
            <input type="text" name="apellido" value="<?= $datos['apellido'] ?>" required placeholder="Apellido">
            <input type="email" name="correo" value="<?= $datos['correo'] ?>" required placeholder="Correo">
            <input type="text" name="fono" value="<?= $datos['fono'] ?>" required placeholder="Teléfono">

            <select name="tipo_u" required>
                <option value="">Seleccione tipo</option>
                <option value="1" <?= $datos['tipo_u'] == 1 ? 'selected' : '' ?>>Administrador</option>
                <option value="2" <?= $datos['tipo_u'] == 2 ? 'selected' : '' ?>>Encargado EMPA</option>
            </select>

            <!-- Botones alineados -->
            <div class="botones-acciones">
                <a href="<?= BASE_URL ?>/index.php?url=usuario/index" class="btn-volver">Volver</a>
                <button type="submit" class="btn-guardar">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../plantillas/footer.php'; ?>
