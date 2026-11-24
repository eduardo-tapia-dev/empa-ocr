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
    </style>
</head>
<body>

    <img src="../img/ucen.png" alt="Logo" class="logo">
    

    <h1>Resultados del Análisis EMPA</h1>

    <div class="contenedor">
        <div class="caja">
            <h2>Resultados</h2>
            <div class="campo"><strong>RUT:</strong> <?php echo htmlspecialchars($rut); ?></div>
            <div class="campo"><strong>Edad:</strong> <?php echo htmlspecialchars($edad); ?> años</div>
            <div class="campo"><strong>Sexo:</strong> <?php echo htmlspecialchars($sexo); ?></div>
            <div class="campo"><strong>Bebe:</strong> <?php echo htmlspecialchars($bebe); ?></div>
            <div class="campo"><strong>Fuma:</strong> <?php echo htmlspecialchars($fuma); ?></div>
            <div class="campo"><strong>Peso:</strong> <?php echo htmlspecialchars($peso); ?> kg</div>
            <div class="campo"><strong>Talla:</strong> <?php echo htmlspecialchars($talla); ?> cm</div>
            <div class="campo"><strong>Circunferencia cintura:</strong> <?php echo htmlspecialchars($cintura); ?> cm</div>
            <div class="campo"><strong>IMC:</strong> <?php echo htmlspecialchars($imc); ?></div>
            <div class="campo"><strong>Estado nutricional:</strong> <?php echo htmlspecialchars($estado); ?></div>
            <div class="campo"><strong>PAS:</strong> <?php echo htmlspecialchars($pas); ?> mmHg</div>
            <div class="campo"><strong>PAD:</strong> <?php echo htmlspecialchars($pad); ?> mmHg</div>
            <div class="campo"><strong>Colesterol:</strong> <?php echo htmlspecialchars(empty($colesterol) ? 'N/A' : $colesterol); ?></div>
            <div class="campo"><strong>Papanicolau:</strong> <?php echo htmlspecialchars($estadoPap); ?></div>
            <div class="campo"><strong>Último PAP:</strong> <?php echo htmlspecialchars($ultimoPap); ?></div>
            <div class="campo"><strong>Mamografía:</strong> <?php echo htmlspecialchars($mamografia); ?></div>
        </div>
    </div>

    <a href="/xampp/EMPA/public/index.php?url=menu" class="boton-volver">Analizar otro examen</a>

</body>
</html>
