<?php require_once __DIR__ . '/../plantillas/header.php'; ?>

<!-- Enlace al CSS -->
<link rel="stylesheet" href="<?= BASE_URL ?>/../css/crearusuario.css">

<!-- Logo MINSAL arriba -->
<div class="logo-superior">
    <img src="<?= BASE_URL ?>/../img/minsal.png" alt="Logo MINSAL" class="logo-minsal">
</div>

<!-- Contenedor centrado del formulario -->
<div class="usuarios-centro">
    <div class="formulario-wrapper">
        <h2 class="titulo">Agregar Usuario</h2>

        <form action="<?= BASE_URL ?>/index.php?url=usuario/guardar" method="POST" class="formulario-usuario">
            <input type="text" name="rut" placeholder="RUT" required>
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellido" placeholder="Apellido" required>
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="text" name="fono" placeholder="Teléfono" required>

            <select name="tipo_u" required>
                <option value="">Seleccione tipo</option>
                <option value="1">Administrador</option>
                <option value="2">Encargado EMPA</option>
            </select>

            <!-- Botones alineados -->
            <div class="botones-acciones">
                <a href="<?= BASE_URL ?>/index.php?url=usuario/index" class="btn-volver">Volver</a>
                <button type="submit" class="btn-guardar">Guardar</button>
            </div>
        </form>
    </div>
</div>

<?php if (isset($error)): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if ($error === 'rut_existente'): ?>
            Swal.fire({
                icon: 'error',
                title: 'RUT ya registrado',
                text: 'El RUT ingresado ya existe.',
                confirmButtonText: 'Volver'
            });
        <?php elseif ($error === 'correo_existente'): ?>
            Swal.fire({
                icon: 'error',
                title: 'Correo ya registrado',
                text: 'El correo ingresado ya existe.',
                confirmButtonText: 'Volver'
            });
        <?php elseif ($error === 'error_general'): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo registrar el usuario.',
                confirmButtonText: 'Volver'
            });
        <?php endif; ?>
    </script>
<?php endif; ?>

<?php require_once __DIR__ . '/../plantillas/footer.php'; ?>
