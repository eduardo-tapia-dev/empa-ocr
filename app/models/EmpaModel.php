<?php

namespace App\Models;

use PDO;

class EmpaModel
{
    private $db;

    public function __construct(PDO $conexion)
    {
        $this->db = $conexion;
    }

    public function insertarEmpa($datos)
    {
        $sql = "INSERT INTO empa (
                    edad, sexo, bebe, fuma, peso, talla, circunferencia, IMC, sobrepeso,
                    PAS, PAD, glicemia, papanicolau, ult_pap, colesterol,
                    mamografia
                ) VALUES (
                    :edad, :sexo, :bebe, :fuma, :peso, :talla, :circunferencia, :IMC, :sobrepeso,
                    :PAS, :PAD, :glicemia, :papanicolau, :ult_pap, :colesterol,
                    :mamografia
                )";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($datos);
        return $this->db->lastInsertId();
    }

    public function verificarOCrearPaciente($rut, $nombre, $apellido, $sexo = 'Otro', $correo = null, $fono = null)
    {
        $query = "SELECT rut_p FROM paciente WHERE rut_p = :rut";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['rut' => $rut]);

        if ($stmt->rowCount() === 0) {
            $insert = "INSERT INTO paciente (rut_p, nombre, apellido, sexo, correo, fono_contacto)
                       VALUES (:rut, :nombre, :apellido, :sexo, :correo, :fono)";
            $stmt = $this->db->prepare($insert);
            $stmt->execute([
                'rut' => $rut,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'sexo' => $sexo,
                'correo' => $correo,
                'fono' => $fono
            ]);
        }
    }

    public function insertarAtencion($rutUsuario, $rutPaciente, $idEmpa)
    {
        $sql = "INSERT INTO atenciones (fecha, rut, rut_p, id_empa)
                VALUES (CURRENT_DATE, :rutUsuario, :rutPaciente, :idEmpa)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'rutUsuario' => $rutUsuario,
            'rutPaciente' => $rutPaciente,
            'idEmpa' => $idEmpa
        ]);
    }

    public function insertarRecomendaciones($idEmpa, $recomendaciones)
    {
        $sql = "INSERT INTO recomendaciones (id_emp, recomendacion) VALUES (:idEmpa, :reco)";
        $stmt = $this->db->prepare($sql);

        foreach ($recomendaciones as $reco) {
            $stmt->execute([
                'idEmpa' => $idEmpa,
                'reco' => trim($reco)
            ]);
        }
    }

    public function obtenerEmpaPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM empa WHERE id_emp = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
