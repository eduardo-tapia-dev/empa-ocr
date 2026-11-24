<?php

namespace App\Models;

class Usuario {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function verificarLogin($rut, $clave) {
        $claveHash = hash('sha512', $clave);
        $sql = "SELECT * FROM usuario WHERE rut = :rut AND password = :password";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':rut' => $rut,
            ':password' => $claveHash
        ]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function registrar($rut, $nombre, $apellido, $correo, $fono, $password, $tipo_u) {
        $claveHash = hash('sha512', $password);
        $sql = "INSERT INTO usuario (rut, nombre, apellido, correo, fono, password, tipo_u)
                VALUES (:rut, :nombre, :apellido, :correo, :fono, :password, :tipo_u)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':rut' => $rut,
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':correo' => $correo,
            ':fono' => $fono,
            ':password' => $claveHash,
            ':tipo_u' => $tipo_u
        ]);
    }

    public function existeRut($rut) {
        $stmt = $this->db->prepare("SELECT rut FROM usuario WHERE rut = :rut");
        $stmt->execute([':rut' => $rut]);
        return $stmt->rowCount() > 0;
    }

    public function existeCorreo($correo) {
        $stmt = $this->db->prepare("SELECT correo FROM usuario WHERE correo = :correo");
        $stmt->execute([':correo' => $correo]);
        return $stmt->rowCount() > 0;
    }

    public function obtenerUsuariosFiltrados($rut, $nombre, $apellido, $limite, $offset) {
        $sql = "SELECT u.rut, u.nombre, u.apellido, u.correo, u.fono, u.tipo_u, t.descripcion AS tipo_nombre 
                FROM usuario u 
                JOIN tipo_usuario t ON u.tipo_u = t.tipo_u
                WHERE 1=1";
        $params = [];

        if (!empty($rut)) {
            $sql .= " AND u.rut LIKE :rut";
            $params[':rut'] = "%$rut%";
        }
        if (!empty($nombre)) {
            $sql .= " AND u.nombre LIKE :nombre";
            $params[':nombre'] = "%$nombre%";
        }
        if (!empty($apellido)) {
            $sql .= " AND u.apellido LIKE :apellido";
            $params[':apellido'] = "%$apellido%";
        }

        $sql .= " ORDER BY u.nombre ASC LIMIT :limite OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limite', (int)$limite, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function contarUsuariosFiltrados($rut, $nombre, $apellido) {
        $sql = "SELECT COUNT(*) as total FROM usuario u WHERE 1=1";
        $params = [];

        if (!empty($rut)) {
            $sql .= " AND u.rut LIKE :rut";
            $params[':rut'] = "%$rut%";
        }
        if (!empty($nombre)) {
            $sql .= " AND u.nombre LIKE :nombre";
            $params[':nombre'] = "%$nombre%";
        }
        if (!empty($apellido)) {
            $sql .= " AND u.apellido LIKE :apellido";
            $params[':apellido'] = "%$apellido%";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
    }

    public function obtenerUsuarios() {
        $sql = "SELECT u.rut, u.nombre, u.apellido, u.correo, u.fono, u.tipo_u, t.descripcion AS tipo_nombre 
                FROM usuario u 
                JOIN tipo_usuario t ON u.tipo_u = t.tipo_u
                ORDER BY u.nombre ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function obtenerUsuariosPaginados($limite, $offset) {
        $sql = "SELECT u.rut, u.nombre, u.apellido, u.correo, u.fono, u.tipo_u, t.descripcion AS tipo_nombre 
                FROM usuario u 
                JOIN tipo_usuario t ON u.tipo_u = t.tipo_u
                LIMIT :limite OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function contarUsuarios() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM usuario");
        return $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
    }

    public function obtenerPorRut($rut) {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE rut = :rut");
        $stmt->execute([':rut' => $rut]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function actualizar($data) {
        $sql = "UPDATE usuario 
                SET nombre = :nombre, apellido = :apellido, correo = :correo, 
                    fono = :fono, tipo_u = :tipo_u 
                WHERE rut = :rut";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre' => $data['nombre'],
            ':apellido' => $data['apellido'],
            ':correo' => $data['correo'],
            ':fono' => $data['fono'],
            ':tipo_u' => $data['tipo_u'],
            ':rut' => $data['rut']
        ]);
    }

    public function eliminar($rut) {
        $stmt = $this->db->prepare("DELETE FROM usuario WHERE rut = :rut");
        return $stmt->execute([':rut' => $rut]);
    }
}
