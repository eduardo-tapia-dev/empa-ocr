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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Escáner Web Alta Resolución</title>
  <link rel="stylesheet" href="../css/camara.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="container">
    <img src="../img/logo.png" alt="Logo" class="logo">
    <h2>Captura de EMPA</h2>

    <div id="mensajeError"></div>
    <div id="procesando">Procesando imagen, por favor espere...</div>

    <div class="video-wrapper">
      <video id="video" autoplay playsinline></video>
      <button id="captureBtn">📸</button>
    </div>

    <canvas id="canvas"></canvas>

    <div class="img-row">
      <div class="preview-container" id="container1" style="display:none;">
        <img id="resultado1" alt="Imagen 1" />
        <button class="retry-btn" onclick="reiniciarCaptura(1)">Reintentar</button>
      </div>

      <div class="preview-container" id="container2" style="display:none;">
        <img id="resultado2" alt="Imagen 2" />
        <button class="retry-btn" onclick="reiniciarCaptura(2)">Reintentar</button>
      </div>
    </div>

    <button class="submit-btn" onclick="enviarAlServidor()">Analizar EMPA</button>

    <!-- Botón volver -->
    <a href="/xampp/EMPA/public/index.php?url=menu" class="btn-volver-menu">← Volver al menú de escaneo</a>
  </div>

<script>
  const video = document.getElementById('video');
  const canvas = document.getElementById('canvas');
  const ctx = canvas.getContext('2d');
  const mensajeError = document.getElementById('mensajeError');
  const procesando = document.getElementById('procesando');
  const resultado1 = document.getElementById('resultado1');
  const resultado2 = document.getElementById('resultado2');
  const container1 = document.getElementById('container1');
  const container2 = document.getElementById('container2');
  let contadorCaptura = 1;
  let blob1 = null;
  let blob2 = null;

  navigator.mediaDevices.getUserMedia({
    video: {
      width: { ideal: 3840 },
      height: { ideal: 2160 },
      facingMode: "environment"
    }
  })
  .then(stream => {
    video.srcObject = stream;
  })
  .catch(err => alert("Error accediendo a la cámara"));

  document.getElementById('captureBtn').onclick = () => {
    mensajeError.style.display = "none";
    procesando.style.display = "block";

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    const dataURL = canvas.toDataURL('image/jpeg');

    fetch('http://localhost:5000/procesar', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ imagen: dataURL })
    })
    .then(res => {
      if (!res.ok) return res.json().then(data => { throw new Error(data.error); });
      return res.blob();
    })
    .then(blob => {
      const imgUrl = URL.createObjectURL(blob);
      if (contadorCaptura === 1) {
        resultado1.src = imgUrl;
        container1.style.display = "block";
        blob1 = blob;
      } else {
        resultado2.src = imgUrl;
        container2.style.display = "block";
        blob2 = blob;
      }
      contadorCaptura = Math.min(2, contadorCaptura + 1);
      procesando.style.display = "none";
    })
    .catch(err => {
      mensajeError.textContent = err.message || "Error en el procesamiento";
      mensajeError.style.display = "block";
      procesando.style.display = "none";
      setTimeout(() => mensajeError.style.display = "none", 5000);
    });
  };

  function reiniciarCaptura(num) {
    if (num === 1) {
      resultado1.src = "";
      container1.style.display = "none";
      contadorCaptura = 1;
      blob1 = null;
    } else {
      resultado2.src = "";
      container2.style.display = "none";
      contadorCaptura = 2;
      blob2 = null;
    }
  }

  function enviarAlServidor() {
    if (!blob1 || !blob2) {
      mensajeError.textContent = "Debes capturar ambas partes del examen";
      mensajeError.style.display = "block";
      return;
    }

    const formData = new FormData();
    formData.append('imagen1', blob1, 'captura1.jpg');
    formData.append('imagen2', blob2, 'captura2.jpg');
    formData.append('rut', '<?php echo $_SESSION['rut']; ?>');

    Swal.fire({
      title: 'Analizando...',
      text: 'Esto puede tardar unos segundos, por favor espera.',
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    fetch('../public/index.php?url=lectura', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(data => {
      Swal.close();
      document.open();
      document.write(data);
      document.close();
    })
    .catch(error => {
      console.error(error);
      Swal.fire({
        icon: 'error',
        title: 'Error al enviar',
        text: 'No se pudo conectar con el servidor PHP.',
      });
    });
  }
</script>
</body>
</html>
