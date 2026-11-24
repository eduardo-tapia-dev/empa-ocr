<?php
namespace App\Models;

use PDO;

class Examen {
    private $conexion;

    public function __construct(PDO $conexion) {
        $this->conexion = $conexion;
    }

    /**
     * Obtiene resultados paginados con filtros aplicados
     */
    public function obtenerPaginados($offset, $limite, $rutPaciente = null, $rutFuncionario = null, $fecha = null) {
        $sql = "SELECT empa.*, atenciones.rut_p, atenciones.rut, atenciones.fecha,
                       usuario.nombre AS nombre_funcionario, usuario.apellido AS apellido_funcionario
                FROM empa
                INNER JOIN atenciones ON atenciones.id_empa = empa.id_emp
                LEFT JOIN usuario ON usuario.rut = atenciones.rut
                WHERE 1=1";

        $params = [];

        if (!empty($rutPaciente)) {
            $sql .= " AND atenciones.rut_p LIKE ?";
            $params[] = "%$rutPaciente%";
        }

        if (!empty($rutFuncionario)) {
            $sql .= " AND atenciones.rut LIKE ?";
            $params[] = "%$rutFuncionario%";
        }

        if (!empty($fecha)) {
            $sql .= " AND DATE(atenciones.fecha) = ?";
            $fechaFormateada = date('Y-m-d', strtotime(str_replace('/', '-', $fecha)));
            $params[] = $fechaFormateada;
        }

        $sql .= " ORDER BY atenciones.fecha DESC LIMIT $limite OFFSET $offset";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cuenta el total de exámenes que coinciden con los filtros
     */
    public function contarFiltrados($rutPaciente = null, $rutFuncionario = null, $fecha = null) {
        $sql = "SELECT COUNT(*) as total FROM empa
                INNER JOIN atenciones ON atenciones.id_empa = empa.id_emp
                LEFT JOIN paciente ON paciente.rut_p = atenciones.rut_p
                LEFT JOIN usuario ON usuario.rut = atenciones.rut
                WHERE 1=1";

        $params = [];

        if (!empty($rutPaciente)) {
            $sql .= " AND atenciones.rut_p LIKE ?";
            $params[] = "%$rutPaciente%";
        }

        if (!empty($rutFuncionario)) {
            $sql .= " AND atenciones.rut LIKE ?";
            $params[] = "%$rutFuncionario%";
        }

        if (!empty($fecha)) {
            $sql .= " AND DATE(atenciones.fecha) = ?";
            $fechaFormateada = date('Y-m-d', strtotime(str_replace('/', '-', $fecha)));
            $params[] = $fechaFormateada;
        }

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($params);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        return $fila['total'];
    }

    public function eliminarPorId($id) {
        $this->conexion->prepare("DELETE FROM recomendaciones WHERE id_emp = ?")->execute([$id]);
        $this->conexion->prepare("DELETE FROM atenciones WHERE id_empa = ?")->execute([$id]);
        $this->conexion->prepare("DELETE FROM empa WHERE id_emp = ?")->execute([$id]);
    }

    public function obtenerPorId($id) {
        $stmt = $this->conexion->prepare(
            "SELECT empa.*, atenciones.rut_p 
             FROM empa 
             INNER JOIN atenciones ON atenciones.id_empa = empa.id_emp 
             WHERE empa.id_emp = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($data) {
        $sqlEmpa = "UPDATE empa SET 
                        bebe = :bebe, fuma = :fuma, peso = :peso, talla = :talla, 
                        circunferencia = :circunferencia, IMC = :IMC, sobrepeso = :sobrepeso, 
                        PAS = :PAS, PAD = :PAD, glicemia = :glicemia, 
                        papanicolau = :papanicolau, ult_pap = :ult_pap, 
                        colesterol = :colesterol, mamografia = :mamografia
                    WHERE id_emp = :id_emp";

        $stmt = $this->conexion->prepare($sqlEmpa);
        $stmt->execute([
            ':bebe' => $data['bebe'],
            ':fuma' => $data['fuma'],
            ':peso' => $data['peso'],
            ':talla' => $data['talla'],
            ':circunferencia' => $data['circunferencia'],
            ':IMC' => $data['IMC'],
            ':sobrepeso' => $data['sobrepeso'],
            ':PAS' => $data['PAS'],
            ':PAD' => $data['PAD'],
            ':glicemia' => $data['glicemia'],
            ':papanicolau' => $data['papanicolau'],
            ':ult_pap' => $data['ult_pap'],
            ':colesterol' => $data['colesterol'],
            ':mamografia' => $data['mamografia'],
            ':id_emp' => $data['id_emp']
        ]);

        $stmt2 = $this->conexion->prepare("UPDATE atenciones SET rut_p = :rut_p WHERE id_empa = :id_empa");
        $stmt2->execute([
            ':rut_p' => $data['rut_p'],
            ':id_empa' => $data['id_emp']
        ]);
    }

    public function verificarExistenciaPaciente($rut_p) {
        $stmt = $this->conexion->prepare("SELECT COUNT(*) as total FROM paciente WHERE rut_p = ?");
        $stmt->execute([$rut_p]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] > 0;
    }
}
