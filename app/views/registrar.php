<?php if (!defined('BASE_URL')) define('BASE_URL', '/xampp/EMPA/public'); ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro EMPA - MINSAL</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/../css/registrar.css?v=2">
</head>
<body>
  <div class="container">
    <div class="general">

      <!-- Panel izquierdo con logo y título -->
      <div class="left-panel">
        <img src="<?= BASE_URL ?>/../img/minsal.png" alt="Logo MINSAL" class="logo">
        <h1>Panel de control<br>datos EMPA</h1>
      </div>

      <!-- Tarjeta de registro -->
      <div class="register-card">
        <h2>Registrarse</h2>

        <form action="<?= BASE_URL ?>/index.php?url=registro" method="POST">
          <input type="text" name="rut" placeholder="👤 Rut" required>
          <input type="text" name="nombre" placeholder="👤 Nombre" required>
          <input type="text" name="apellido" placeholder="👤 Apellido" required>
          <input type="text" name="correo" placeholder="📧 Correo" required>
          <input type="text" name="fono" placeholder="📱 Número telefónico" required>
          <input type="password" name="password" placeholder="🔐 Contraseña" required>

          <!-- Corrección de tipo de usuario -->
          <select name="tipo_u" required>
            <option value="" disabled selected>Selecciona tipo de usuario</option>
            <option value="2">Administrador</option>
            <option value="1">Usuario</option>
          </select>

          <!-- Botones estilizados -->
          <div class="botones-acciones">
            <a href="<?= BASE_URL ?>/index.php?url=login" class="btn-volver">Volver</a>
            <button type="submit" class="btn-guardar">Confirmar</button>
          </div>

          <!-- Pie con redes sociales -->
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
</body>
</html>
