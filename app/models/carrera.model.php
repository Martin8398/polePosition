<?php

class CarreraModel
{
    private $db;
    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=poleposition;charset=utf8', 'root', '');
    }

    public function getCarreras()
    {
        $query = $this->db->prepare('SELECT* FROM carreras');
        $query->execute();
        $carreras = $query->fetchAll(PDO::FETCH_OBJ);
        return $carreras;
    }

    public function getCarrera($id)
    {
        $query = $this->db->prepare('SELECT* FROM carreras WHERE carrera_id = :carrera_id');
        $query->bindParam('carrera_id', $id);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function addCarrera($fecha, $vueltas)
    {
        $query = $this->db->prepare("INSERT INTO carreras (fecha, vueltas) VALUES (:fecha, :vueltas)");
        $query->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $query->bindParam(':vueltas', $vueltas, PDO::PARAM_INT);
        $query->execute();
        return $this->db->lastInsertId(); // para luego asignar pilotos
    }

    // obtener pilotos de una carrera ordenados por posición

    // public function getResultadosCarrera($carrera_id)
    // {
    //     $query = $this->db->prepare("
    //     SELECT p.nombre,p.apellido, r.tiempo
    //     FROM resultados r
    //     INNER JOIN pilotos p ON r.piloto_id = p.piloto_id
    //     WHERE r.carrera_id = :id
    //     ORDER BY r.posicion ASC
    // ");
    //     $query->bindParam(':id', $carrera_id, PDO::PARAM_INT);
    //     $query->execute();
    //     return $query->fetchAll(PDO::FETCH_OBJ);
    // }

    // r.posicion, 
    //         r.tiempo, 
    //         p.piloto_id,
    //         p.nombre, 
    //         p.apellido
    //     FROM resultados r
    //     INNER JOIN pilotos p ON r.piloto_id = p.piloto_id
    //     WHERE r.carrera_id = :id
    //     ORDER BY r.posicion ASC

    // asignaa un piloto a una carrera con su posición

    public function addResultado($carrera_id, $piloto_id, $posicion)
    {
        $query = $this->db->prepare("
            INSERT INTO resultados (carrera_id, piloto_id, posicion)
            VALUES (:carrera_id, :piloto_id, :posicion)
            ");
        $query->bindParam(':carrera_id', $carrera_id, PDO::PARAM_INT);
        $query->bindParam(':piloto_id', $piloto_id, PDO::PARAM_INT);
        $query->bindParam(':posicion', $posicion, PDO::PARAM_INT);
        $query->execute();
    }


    public function getGanador($carrera_id)
    {
        $query = $this->db->prepare("
        SELECT p.nombre, p.apellido, r.tiempo
        FROM resultados r
        INNER JOIN pilotos p ON r.piloto_id = p.piloto_id
        WHERE r.carrera_id = :id
        ORDER BY r.posicion ASC
        LIMIT 1
    ");
        $query->bindParam(':id', $carrera_id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }
}
