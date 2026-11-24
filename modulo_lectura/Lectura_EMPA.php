<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['rut'])) {
    header("Location: /xampp/EMPA/public/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Escanear EMPA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/Lectura_EMPA.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body oninput="toggleCampos()">

    <img src="../img/logo.png" alt="Logo" class="logo">

    <div class="container">
        <form id="formulario-empa" action="../public/index.php?url=lectura" method="post" enctype="multipart/form-data">
            <h1>Analizar EMPA</h1>

            <label for="rut">RUT Encargado:</label>
            <input type="text" name="rut" id="rut" value="<?= htmlspecialchars($_SESSION['rut']) ?>" readonly style="font-weight: bold;">

            <div class="campo-imagen">
                <label>Imagen del examen (parte 1):</label>
                <input type="file" name="imagen1" accept="image/*">
            </div>

            <div class="campo-imagen">
                <label>Imagen del examen (parte 2):</label>
                <input type="file" name="imagen2" accept="image/*">
            </div>

            <hr>

            <div class="campo-pdf">
                <label>O subir un PDF con múltiples EMPA:</label>
                <input type="file" name="archivo_pdf" accept="application/pdf">
                <p class="nota">
                    * Si subes un PDF, se usará el RUT ingresado para todos los EMPAs del archivo.
                </p>
            </div>

            <!-- Botones -->
            <div class="botones-container">
                <a href="/xampp/EMPA/public/index.php?url=menu" class="btn-volver">Volver al Menú</a>
                <input type="submit" value="Analizar EMPA" class="btn-analizar">
            </div>
        </form>
    </div>

    <footer>
        © 2025 EMPA - Ministerio de Salud
    </footer>

    <script>
        function toggleCampos() {
            const imagen1 = document.querySelector('input[name="imagen1"]').value;
            const imagen2 = document.querySelector('input[name="imagen2"]').value;
            const archivoPDF = document.querySelector('input[name="archivo_pdf"]').value;

            const imageFields = document.querySelectorAll('.campo-imagen');
            const pdfField = document.querySelector('.campo-pdf');

            if (archivoPDF) {
                imageFields.forEach(el => el.style.display = 'none');
            } else {
                imageFields.forEach(el => el.style.display = 'block');
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById('formulario-empa');

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Analizando...',
                    text: 'Esto puede tardar unos segundos, por favor espera.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                setTimeout(() => {
                    form.submit();
                }, 300);
            });
        });
    </script>
</body>
</html>
