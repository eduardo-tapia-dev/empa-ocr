<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$procesados = $_SESSION['procesados_pdf'] ?? 0;
$datosPDF = $_SESSION['datos_pdf'] ?? [];
if (!isset($datosProcesados) || !is_array($datosProcesados)) {
    echo "<p>No hay datos disponibles para mostrar.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado Análisis EMPA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/Lectura_EMPA.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
            font-family: 'Work Sans', Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }
        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 80px;
        }
        .contenedor {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        .caja {
            background: white;
            padding: 25px;
            flex: 1 1 400px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #ccc;
            box-sizing: border-box;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        h2 {
            text-align: center;
            color: #0f69b4;
            margin-bottom: 20px;
        }
        .campo {
            margin-bottom: 15px;
        }
        .campo strong {
            display: inline-block;
            width: 180px;
        }
        .boton-volver {
            display: block;
            width: 200px;
            text-align: center;
            margin: 40px auto 0;
            padding: 10px;
            background: #0f69b4;
            color: white;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
        }
        .boton-volver:hover {
            background: #0d5ea1;
        }

        @media (max-width: 768px) {
            .caja {
                flex: 1 1 100%;
            }
            .logo {
                width: 60px;
                top: 10px;
                left: 10px;
            }
        }

        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 80px;
        }

        .boton-volver {
            display: block;
            width: 200px;
            text-align: center;
            margin: 40px auto 0;
            padding: 10px;
            background: #0f69b4;
            color: white;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
        }
        .boton-volver:hover {
            background: #0d5ea1;
        }

    </style>
</head>
    <img src="../img/ucen.png" alt="Logo" class="logo">
    <body>
        <img src="../img/ucen.png" alt="Logo" class="logo">

<h1>Resultados del Análisis EMPA</h1>

<div class="contenedor">
    <div class="caja" style="width: 100%;">
        <h2>Se procesaron <?= htmlspecialchars(count($datosProcesados)) ?> EMPA<?= count($datosProcesados) === 1 ? '' : 's' ?></h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px;">
                <thead style="background: #0f69b4; color: white;">
                    <tr>
                        <th>RUT</th>
                        <th>Edad</th>
                        <th>Sexo</th>
                        <th>Peso</th>
                        <th>Talla</th>
                        <th>Cintura</th>
                        <th>IMC</th>
                        <th>Estado</th>
                        <th>PAS</th>
                        <th>PAD</th>
                        <th>Glicemia</th>
                        <th>Colesterol</th>
                        <th>PAP</th>
                        <th>Último PAP</th>
                        <th>Mamografía</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datosProcesados as $empa): ?>
                        <tr style="background: #fff; border-bottom: 1px solid #ddd; text-align: center;">
                            <td><?= htmlspecialchars($empa['rut'] ?? '') ?></td>
                            <td><?= htmlspecialchars($empa['edad'] ?? '') ?></td>
                            <td><?= htmlspecialchars($empa['sexo'] ?? '') ?></td>
                            <td><?= htmlspecialchars($empa['peso'] ?? '') ?></td>
                            <td><?= htmlspecialchars($empa['talla'] ?? '') ?></td>
                            <td><?= htmlspecialchars($empa['circunferencia'] ?? '') ?></td>
                            <td><?= htmlspecialchars($empa['imc'] ?? '') ?></td>
                            <td><?= htmlspecialchars($empa['estado'] ?? '') ?></td>
                            <td><?= htmlspecialchars($empa['pas'] ?? '') ?></td>
                            <td><?= htmlspecialchars($empa['pad'] ?? '') ?></td>
                            <td><?= htmlspecialchars($empa['glicemia'] ?? '') ?></td>
                            <td><?= htmlspecialchars($empa['colesterol'] ?? '') ?></td>
                            <td><?= htmlspecialchars($empa['papanicolau'] ?? '') ?></td>
                            <td><?= htmlspecialchars($empa['ult_pap'] ?? '') ?></td>
                            <td><?= htmlspecialchars($empa['mamografia'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<a href="/xampp/EMPA/public/index.php?url=menu" class="boton-volver">Analizar otro examen</a>

</body>
</html>