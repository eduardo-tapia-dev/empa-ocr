<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/Examen.php';

class ExamenController {

    public function index() {
        $examenModel = new \App\Models\Examen($GLOBALS['conexion']);

        // Parámetros GET
        $paginaActual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
        $limite = isset($_GET['limite']) ? (int)$_GET['limite'] : 10;
        $rutPaciente = $_GET['rut_p'] ?? null;
        $rutFuncionario = $_GET['rut_f'] ?? null;
        $fecha = $_GET['fecha'] ?? null;

        $offset = ($paginaActual - 1) * $limite;

        // Obtener exámenes paginados
        $examenes = $examenModel->obtenerPaginados($offset, $limite, $rutPaciente, $rutFuncionario, $fecha);

        $totalRegistros = $examenModel->contarFiltrados($rutPaciente, $rutFuncionario, $fecha);
        $totalPaginas = max(1, ceil($totalRegistros / $limite));

        // Cargar vista
        require_once __DIR__ . '/../views/examen/index.php';
    }

    public function eliminar($id) {
        $examenModel = new \App\Models\Examen($GLOBALS['conexion']);
        $examenModel->eliminarPorId($id);
        header("Location: " . BASE_URL . "/index.php?url=examen/index");
        exit();
    }

    public function editar($id) {
        $examenModel = new \App\Models\Examen($GLOBALS['conexion']);
        $examen = $examenModel->obtenerPorId($id);
        require_once __DIR__ . '/../views/examen/editar.php';
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_emp' => $_POST['id_emp'],
                'rut_p' => $_POST['rut_p'],
                'bebe' => $_POST['bebe'],
                'fuma' => $_POST['fuma'],
                'peso' => $_POST['peso'],
                'talla' => $_POST['talla'],
                'circunferencia' => $_POST['circunferencia'],
                'IMC' => $_POST['IMC'],
                'sobrepeso' => $_POST['sobrepeso'],
                'PAS' => $_POST['PAS'],
                'PAD' => $_POST['PAD'],
                'glicemia' => $_POST['glicemia'],
                'sifilis' => $_POST['sifilis'],
                'tos' => $_POST['tos'],
                'baciloscopia' => $_POST['baciloscopia'],
                'papanicolau' => $_POST['papanicolau'],
                'ult_pap' => $_POST['ult_pap'],
                'colesterol' => $_POST['colesterol'],
                'mamografia' => $_POST['mamografia'],
                'mam_otr_edad' => $_POST['mam_otr_edad'],
                'observ' => $_POST['observ']
            ];

            $examenModel = new \App\Models\Examen($GLOBALS['conexion']);

            // Verificar existencia del paciente
            $existe = $examenModel->verificarExistenciaPaciente($data['rut_p']);
            if (!$existe) {
                echo "<script>
                        alert('El RUT del paciente ingresado no existe. Primero debe registrarlo.');
                        window.location.href = '" . BASE_URL . "/index.php?url=examen/editar&id=" . $data['id_emp'] . "';
                      </script>";
                exit();
            }

            $examenModel->actualizar($data);
            header("Location: " . BASE_URL . "/index.php?url=examen/index");
            exit();
        }
    }
}
