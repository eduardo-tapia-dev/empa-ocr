<?php if (!defined('BASE_URL')) define('BASE_URL', '/xampp/EMPA/public'); ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login EMPA - MINSAL</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/../css/login.css?v=2">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="container">
    <div class="general">

      <!-- Panel izquierdo con logo y título -->
      <div class="left-panel">
        <img src="<?= BASE_URL ?>/../img/minsal.png" alt="Logo MINSAL" class="logo">
        <h1>Panel de control<br>datos EMPA</h1>
      </div>

      <!-- Tarjeta de login -->
      <div class="login-card">
        <h2>Iniciar Sesión</h2>

        <form action="<?= BASE_URL ?>/index.php?url=login" method="POST">
          <input type="text" name="rut" placeholder="👤 Rut" required>
          <input type="password" name="password" placeholder="🔐 Contraseña" required>

          <div class="botones-acciones">
            <a href="<?= BASE_URL ?>/index.php?url=registro" class="btn-volver">Registrarse</a>
            <button type="submit" class="btn-guardar">Ingresar</button>
          </div>

          <div class="footer">
            <div class="socials">
              <a href="https://x.com/superdesalud?lang=es" target="_blank">
                <img src="<?= BASE_URL ?>/../img/x.png" alt="X" class="icon">
              </a>
              <a href="https://www.instagram.com/ministeriosalud/?hl=es" target="_blank">
                <img src="<?= BASE_URL ?>/../img/instagram.png" alt="Instagram" class="icon">
              </a>
              <a href="https://www.youtube.com/user/ministeriosaludchile" target="_blank">
                <img src="<?= BASE_URL ?>/../img/youtube.png" alt="YouTube" class="icon">
              </a>
            </div>
            <div class="spacer"></div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- SweetAlert para éxito -->
  <?php if (isset($_GET['success'])): ?>
    <script>
      Swal.fire({
        icon: 'success',
        title: '¡Registro exitoso!',
        text: 'Ya puedes iniciar sesión.',
        confirmButtonColor: '#0d5c88'
      });
    </script>
  <?php endif; ?>

  <!-- SweetAlert para error -->
  <?php if (isset($_GET['error'])): ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Rut y contraseña no coinciden.',
        confirmButtonColor: '#d33'
      });
    </script>
  <?php endif; ?>
</body>
</html>
