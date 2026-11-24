<?php

namespace App\Controllers;

class MetricaController {
    private $db;

    public function __construct($conexion)
    {
        $this->db = $conexion;
    }

    public function index()
    {
        // 1. Exámenes por mes
        $sql1 = "SELECT MONTH(fecha) as mes, COUNT(*) as total FROM atenciones GROUP BY mes ORDER BY mes";
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->execute();
        $examenesPorMes = $stmt1->fetchAll(\PDO::FETCH_ASSOC);

        // 2. Promedio de IMC por mes
        $sql2 = "SELECT MONTH(atenciones.fecha) as mes, ROUND(AVG(empa.IMC),1) as promedio 
                 FROM empa 
                 INNER JOIN atenciones ON atenciones.id_empa = empa.id_emp
                 GROUP BY mes ORDER BY mes";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->execute();
        $imcPorMes = $stmt2->fetchAll(\PDO::FETCH_ASSOC);

        // 3. Glicemia alta vs normal
        $sql3 = "SELECT 
                    SUM(CASE WHEN glicemia > 100 THEN 1 ELSE 0 END) AS alta,
                    SUM(CASE WHEN glicemia <= 100 THEN 1 ELSE 0 END) AS normal
                 FROM empa";
        $stmt3 = $this->db->prepare($sql3);
        $stmt3->execute();
        $glicemia = $stmt3->fetch(\PDO::FETCH_ASSOC);

        // 4. Presión alta vs normal
        $sql4 = "SELECT 
                    SUM(CASE WHEN PAS >= 140 OR PAD >= 90 THEN 1 ELSE 0 END) AS alta,
                    SUM(CASE WHEN PAS < 140 AND PAD < 90 THEN 1 ELSE 0 END) AS normal
                 FROM empa";
        $stmt4 = $this->db->prepare($sql4);
        $stmt4->execute();
        $presion = $stmt4->fetch(\PDO::FETCH_ASSOC);

        // Vista
        require_once __DIR__ . '/../views/metrica/index.php';
    }
}
