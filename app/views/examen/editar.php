<?php require_once __DIR__ . '/../plantillas/header.php'; ?>

<link rel="stylesheet" href="<?= BASE_URL ?>/../css/exeditar.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="logo-superior">
    <img src="<?= BASE_URL ?>/../img/minsal.png" alt="Logo MINSAL" class="logo-minsal">
</div>

<div class="usuarios-centro">
    <div class="formulario-wrapper">
        <h2 class="titulo">Editar Examen EMPA</h2>

        <form action="<?= BASE_URL ?>/index.php?url=examen/actualizar" method="post" class="form-grid">
            <input type="hidden" name="id_emp" value="<?= $examen['id_emp'] ?>">

            <div class="contenedor-formulario">

                <div class="form-group">
                    <label>RUT Paciente</label>
                    <input type="text" name="rut_p" value="<?= htmlspecialchars($examen['rut_p']) ?>" readonly style="text-align:center;">
                </div>

                <div class="form-group"><label>Edad</label>
                    <input type="text" name="edad" value="<?= $examen['edad'] ?>" readonly style="text-align:center;">
                </div>
                <div class="form-group"><label>Sexo</label>
                    <input type="text" name="sexo" value="<?= $examen['Sexo'] ?>" readonly style="text-align:center;">
                </div>

                <div class="form-group"><label>Bebe</label>
                    <select name="bebe" style="text-align:center;">
                        <option value="SI" <?= $examen['bebe'] === 'SI' ? 'selected' : '' ?>>SI</option>
                        <option value="NO" <?= $examen['bebe'] === 'NO' ? 'selected' : '' ?>>NO</option>
                    </select>
                </div>

                <div class="form-group"><label>Fuma</label>
                    <select name="fuma" style="text-align:center;">
                        <option value="SI" <?= $examen['fuma'] === 'SI' ? 'selected' : '' ?>>SI</option>
                        <option value="NO" <?= $examen['fuma'] === 'NO' ? 'selected' : '' ?>>NO</option>
                    </select>
                </div>

                <div class="form-group"><label>Peso</label>
                    <input type="number" name="peso" value="<?= $examen['peso'] ?>" style="text-align:center;">
                </div>
                <div class="form-group"><label>Talla</label>
                    <input type="number" name="talla" value="<?= $examen['talla'] ?>" style="text-align:center;">
                </div>
                <div class="form-group"><label>Circunferencia</label>
                    <input type="number" name="circunferencia" value="<?= $examen['circunferencia'] ?>" style="text-align:center;">
                </div>
                <div class="form-group"><label>IMC</label>
                    <input type="text" name="IMC" value="<?= $examen['IMC'] ?>" style="text-align:center;">
                </div>
                <div class="form-group"><label>Sobrepeso</label>
                    <input type="text" name="sobrepeso" value="<?= $examen['sobrepeso'] ?>" style="text-align:center;">
                </div>
                <div class="form-group"><label>PAS</label>
                    <input type="number" name="PAS" value="<?= $examen['PAS'] ?>" style="text-align:center;">
                </div>
                <div class="form-group"><label>PAD</label>
                    <input type="number" name="PAD" value="<?= $examen['PAD'] ?>" style="text-align:center;">
                </div>
                <div class="form-group"><label>Glicemia</label>
                    <input type="number" name="glicemia" value="<?= $examen['glicemia'] ?>" style="text-align:center;">
                </div>
                <div class="form-group"><label>Papanicolau</label>
                    <select name="papanicolau" style="text-align:center;">
                        <option value="SI" <?= $examen['papanicolau'] === 'SI' ? 'selected' : '' ?>>SI</option>
                        <option value="NO" <?= $examen['papanicolau'] === 'NO' ? 'selected' : '' ?>>NO</option>
                    </select>
                </div>
                <div class="form-group"><label>Último Pap</label>
                    <input type="text" name="ult_pap" value="<?= $examen['ult_pap'] ?>" style="text-align:center;">
                </div>
                <div class="form-group"><label>Colesterol</label>
                    <select name="colesterol" style="text-align:center;">
                        <option value="SI" <?= $examen['colesterol'] === 'SI' ? 'selected' : '' ?>>SI</option>
                        <option value="NO" <?= $examen['colesterol'] === 'NO' ? 'selected' : '' ?>>NO</option>
                    </select>
                </div>
                <div class="form-group"><label>Mamografía</label>
                    <select name="mamografia" style="text-align:center;">
                        <option value="SI" <?= $examen['mamografia'] === 'SI' ? 'selected' : '' ?>>SI</option>
                        <option value="NO" <?= $examen['mamografia'] === 'NO' ? 'selected' : '' ?>>NO</option>
                    </select>
                </div>
            </div>

            <div class="form-botones-extremos">
                <a href="<?= BASE_URL ?>/index.php?url=examen/index" class="btn-volver-lista">Volver a la Lista</a>
                <button type="submit" class="btn-guardar">Actualizar</button>
            </div>
        </form>

        <footer class="footer">
            © 2025 EMPA - Ministerio de Salud
        </footer>
    </div>
</div>
