<?php
class ResultadoModel
{
    private $db;
    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=polePosition;charset=utf8', 'root', '');
    }


    public function addResultado($carrera_id, $piloto_id, $posicion, $tiempo)
    {
        $query = $this->db->prepare("
        INSERT INTO resultados (carrera_id, piloto_id, posicion, tiempo)
        VALUES (:carrera_id, :piloto_id, :posicion, :tiempo)
    ");

        $query->execute([
            ':carrera_id' => $carrera_id,
            ':piloto_id'  => $piloto_id,
            ':posicion'   => $posicion,
            ':tiempo'     => $tiempo
        ]);
    }

    // Trae todos los resultados de una carrera específica
    public function getResultadosCarrera($carrera_id)
    {
        $query = $this->db->prepare("
            SELECT 
                r.resultado_id,
                r.posicion, 
                r.tiempo, 
                p.piloto_id,
                p.nombre, 
                p.apellido
            FROM resultados r
            INNER JOIN pilotos p ON r.piloto_id = p.piloto_id
            WHERE r.carrera_id = :id
            ORDER BY r.posicion ASC
        ");
        $query->bindParam(':id', $carrera_id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function getResultadosPiloto($piloto_id)
    {
        $query = $this->db->prepare("
        SELECT 
            r.posicion,
            r.tiempo,
            c.carrera_id,
            c.fecha,
            c.vueltas
        FROM resultados r
        INNER JOIN carreras c ON r.carrera_id = c.carrera_id
        WHERE r.piloto_id = :id
        ORDER BY c.fecha DESC
    ");
        $query->bindParam(':id', $piloto_id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function updateResultado($resultado_id, $piloto_id, $posicion, $tiempo)
    {
        $query = $this->db->prepare("
        UPDATE resultados 
        SET piloto_id = :piloto_id, posicion = :posicion, tiempo = :tiempo
        WHERE resultado_id = :resultado_id
    ");
        $query->bindParam(':resultado_id', $resultado_id, PDO::PARAM_INT);
        $query->bindParam(':piloto_id', $piloto_id, PDO::PARAM_INT);
        $query->bindParam(':posicion', $posicion, PDO::PARAM_INT);
        $query->bindParam(':tiempo', $tiempo, PDO::PARAM_STR);
        $query->execute();
    }

    public function deleteResultado($resultado_id)
    {
        $query = $this->db->prepare("DELETE FROM resultados WHERE resultado_id = :resultado_id");
        $query->bindParam(':resultado_id', $resultado_id, PDO::PARAM_INT);
        $query->execute();
    }
}
