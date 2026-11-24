<?php require_once __DIR__ . '/../plantillas/header.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Exámenes EMPA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= BASE_URL ?>/../css/examen.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<div class="logo-superior">
    <img src="<?= BASE_URL ?>/../img/minsal.png" alt="Logo MINSAL" class="logo-minsal">
</div>

<div class="usuarios-centro">
    <div class="usuarios-wrapper">
        <h2 class="titulo">Lista de Exámenes EMPA</h2>

        <div class="btn-agregar-container">
            <a href="<?= BASE_URL ?>/index.php?url=menu" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver al Menú
            </a>
        </div>

        <!-- Filtros -->
        <form method="GET" action="<?= BASE_URL ?>/index.php" class="filtro-form">
            <input type="hidden" name="url" value="examen/index">
            <input type="text" name="rut_p" placeholder="RUT Paciente" value="<?= $_GET['rut_p'] ?? '' ?>">
            <input type="text" name="rut_f" placeholder="RUT Funcionario" value="<?= $_GET['rut_f'] ?? '' ?>">
            <input type="text" name="fecha" placeholder="dd-mm-aaaa" value="<?= $_GET['fecha'] ?? '' ?>">

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
                    <th>RUT Paciente</th>
                    <th>RUT Funcionario</th>
                    <th>Funcionario</th>
                    <th>Fecha</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($examenes) === 0): ?>
                    <tr><td colspan="5">No se encontraron exámenes.</td></tr>
                <?php else: ?>
                    <?php foreach ($examenes as $examen): ?>
                       <tr>
    <td><?= htmlspecialchars($examen['rut_p'] ?? '-') ?></td>
    <td><?= htmlspecialchars($examen['rut'] ?? '-') ?></td>
    <td><?= htmlspecialchars(($examen['nombre_funcionario'] ?? '-') . ' ' . ($examen['apellido_funcionario'] ?? '')) ?></td>
    <td><?= isset($examen['fecha']) ? date('d-m-Y', strtotime($examen['fecha'])) : 'N/A' ?></td>
    <td class="opciones">
        <a href="<?= BASE_URL ?>/index.php?url=examen/editar&id=<?= $examen['id_emp'] ?>" title="Editar">
            <i class="fas fa-pen text-blue"></i>
        </a>
        <a href="#" class="btn-eliminar" data-id="<?= $examen['id_emp'] ?>" title="Eliminar">
            <i class="fas fa-trash-alt text-red"></i>
        </a>
    </td>
</tr>

                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="usuarios-footer">
            <div class="total-contador">
                Página <?= $paginaActual ?> de <?= $totalPaginas ?> | Mostrando <?= count($examenes) ?> exámenes
            </div>

            <div class="paginacion-examen">
                <?php
                $baseUrl = BASE_URL . "/index.php?url=examen/index";
                if (!empty($_GET['rut_p'])) $baseUrl .= "&rut_p=" . urlencode($_GET['rut_p']);
                if (!empty($_GET['rut_f'])) $baseUrl .= "&rut_f=" . urlencode($_GET['rut_f']);
                if (!empty($_GET['fecha'])) $baseUrl .= "&fecha=" . urlencode($_GET['fecha']);
                if (!empty($_GET['limite'])) $baseUrl .= "&limite=" . urlencode($_GET['limite']);

                $maxBotones = 7;
                $inicio = max(1, $paginaActual - floor($maxBotones / 2));
                $fin = min($totalPaginas, $inicio + $maxBotones - 1);
                if (($fin - $inicio + 1) < $maxBotones) {
                    $inicio = max(1, $fin - $maxBotones + 1);
                }
                ?>

                <?php if ($paginaActual > 1): ?>
                    <a href="<?= $baseUrl ?>&pagina=<?= $paginaActual - 1 ?>" class="btn-paginacion-examen anterior">Anterior</a>
                <?php else: ?>
                    <span class="btn-paginacion-examen anterior disabled">Anterior</span>
                <?php endif; ?>

                <?php for ($i = $inicio; $i <= $fin; $i++): ?>
                    <?php if ($i == $paginaActual): ?>
                        <span class="btn-paginacion-examen activo"><?= $i ?></span>
                    <?php else: ?>
                        <a href="<?= $baseUrl ?>&pagina=<?= $i ?>" class="btn-paginacion-examen"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($paginaActual < $totalPaginas): ?>
                    <a href="<?= $baseUrl ?>&pagina=<?= $paginaActual + 1 ?>" class="btn-paginacion-examen siguiente">Siguiente</a>
                <?php else: ?>
                    <span class="btn-paginacion-examen siguiente disabled">Siguiente</span>
                <?php endif; ?>
            </div>
        </div>

        <footer class="footer">
            © 2025 EMPA - Ministerio de Salud
        </footer>

        <!-- SweetAlert para eliminación -->
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const botonesEliminar = document.querySelectorAll('.btn-eliminar');
            botonesEliminar.forEach(boton => {
                boton.addEventListener('click', function (e) {
                    e.preventDefault();
                    const id = this.dataset.id;

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esto!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `<?= BASE_URL ?>/index.php?url=examen/eliminar&id=${id}`;
                        }
                    });
                });
            });
        });
        </script>
    </div>
</div>
</body>
</html>
