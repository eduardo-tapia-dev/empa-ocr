<?php require_once __DIR__ . '/../plantillas/header.php'; ?>

<!-- Logo MINSAL arriba -->
<div class="logo-superior">
    <img src="<?= BASE_URL ?>/../img/minsal.png" alt="Logo MINSAL" class="logo-minsal">
</div>

<!-- Contenedor centrado -->
<div class="usuarios-centro">
    <div class="usuarios-wrapper">
        <h2 class="titulo">Gestión de Usuarios</h2>

        <!-- Botones Agregar y Volver -->
        <div class="btn-agregar-container">
            <a href="<?= BASE_URL ?>/index.php?url=usuario/crear" class="btn-agregar">
                <i class="fas fa-user-plus"></i> Agregar
            </a>
            <a href="<?= BASE_URL ?>/index.php?url=menu" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver al Menú
            </a>
        </div>

        <!-- Filtros -->
        <form method="GET" action="<?= BASE_URL ?>/index.php" class="filtro-form">
            <input type="hidden" name="url" value="usuario/index">
            <input type="text" name="rut" placeholder="RUT" value="<?= $_GET['rut'] ?? '' ?>">
            <input type="text" name="nombre" placeholder="Nombre" value="<?= $_GET['nombre'] ?? '' ?>">
            <input type="text" name="apellido" placeholder="Apellido" value="<?= $_GET['apellido'] ?? '' ?>">

            <select name="limite" class="selector-limite">
                <option value="10" <?= ($_GET['limite'] ?? '10') == '10' ? 'selected' : '' ?>>10</option>
                <option value="30" <?= ($_GET['limite'] ?? '10') == '30' ? 'selected' : '' ?>>30</option>
                <option value="50" <?= ($_GET['limite'] ?? '10') == '50' ? 'selected' : '' ?>>50</option>
            </select>

            <button type="submit" class="btn-filtrar">
                <i class="fas fa-search"></i> Filtrar
            </button>
        </form>

        <!-- Tabla -->
        <table class="tabla">
            <thead>
                <tr>
                    <th>Estado</th>
                    <th>RUT</th>
                    <th>Nombre Completo</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Tipo</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td>Activo</td>
                        <td data-label="RUT"><?= $usuario['rut'] ?></td>
                        <td data-label="Nombre Completo"><?= $usuario['nombre'] . ' ' . $usuario['apellido'] ?></td>
                        <td data-label="Correo"><?= $usuario['correo'] ?></td>
                        <td data-label="Teléfono"><?= $usuario['fono'] ?></td>
                        <td data-label="Tipo">
                            <?php
                                if ($usuario['tipo_u'] == 1) echo 'encargado empa';
                                elseif ($usuario['tipo_u'] == 2) echo 'administrador';
                                else echo 'desconocido';
                            ?>
                        </td>
                        <td data-label="Opciones">
                            <a href="<?= BASE_URL ?>/index.php?url=usuario/editar&rut=<?= $usuario['rut'] ?>" title="Editar">
                                <i class="fas fa-pen text-blue"></i>
                            </a>
                            &nbsp;
                            <a href="#" class="btn-eliminar" data-rut="<?= $usuario['rut'] ?>" title="Eliminar">
                                <i class="fas fa-trash text-red"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Notificaciones -->
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Usuario registrado',
                    text: 'El usuario fue agregado exitosamente.',
                    confirmButtonColor: '#0d5c88'
                });
            </script>
        <?php endif; ?>

        <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Usuario actualizado',
                    text: 'El usuario fue modificado exitosamente.',
                    confirmButtonColor: '#0d5c88'
                });
            </script>
        <?php endif; ?>

        <!-- Paginación personalizada -->
        <div class="usuarios-footer">
            <span><?= $totalRegistros ?> Usuarios</span>

            <div class="paginacion-usuario">
                <?php
                $baseUrl = BASE_URL . "/index.php?url=usuario/index";
                if (!empty($_GET['rut'])) $baseUrl .= "&rut=" . urlencode($_GET['rut']);
                if (!empty($_GET['nombre'])) $baseUrl .= "&nombre=" . urlencode($_GET['nombre']);
                if (!empty($_GET['apellido'])) $baseUrl .= "&apellido=" . urlencode($_GET['apellido']);
                if (!empty($_GET['limite'])) $baseUrl .= "&limite=" . intval($_GET['limite']);

                $maxBotones = 7;
                $inicio = max(1, $pagina - floor($maxBotones / 2));
                $fin = min($totalPaginas, $inicio + $maxBotones - 1);
                if (($fin - $inicio + 1) < $maxBotones) {
                    $inicio = max(1, $fin - $maxBotones + 1);
                }
                ?>

                <!-- Anterior -->
                <?php if ($pagina > 1): ?>
                    <a href="<?= $baseUrl ?>&pagina=<?= $pagina - 1 ?>" class="btn-paginacion-usuario anterior">Anterior</a>
                <?php else: ?>
                    <span class="btn-paginacion-usuario anterior disabled">Anterior</span>
                <?php endif; ?>

                <!-- Números -->
                <?php for ($i = $inicio; $i <= $fin; $i++): ?>
                    <?php if ($i == $pagina): ?>
                        <span class="btn-paginacion-usuario activo"><?= $i ?></span>
                    <?php else: ?>
                        <a href="<?= $baseUrl ?>&pagina=<?= $i ?>" class="btn-paginacion-usuario"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <!-- Siguiente -->
                <?php if ($pagina < $totalPaginas): ?>
                    <a href="<?= $baseUrl ?>&pagina=<?= $pagina + 1 ?>" class="btn-paginacion-usuario siguiente">Siguiente</a>
                <?php else: ?>
                    <span class="btn-paginacion-usuario siguiente disabled">Siguiente</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Eliminar con SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const eliminarLinks = document.querySelectorAll('.btn-eliminar');
        eliminarLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const rut = this.dataset.rut;

                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¡No podrás revertir esto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, eliminar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "<?= BASE_URL ?>/index.php?url=usuario/eliminar&rut=" + rut;
                    }
                });
            });
        });
    });
</script>

<?php require_once __DIR__ . '/../plantillas/footer.php'; ?>
