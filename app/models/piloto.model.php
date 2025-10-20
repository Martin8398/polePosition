<?php

class PilotoModel
{
    private $db;
    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=poleposition;charset=utf8', 'root', '');
    }

    public function getPilotos()
    {
        $query = $this->db->prepare('SELECT * FROM pilotos ORDER BY nombre ASC');
        $query->execute();
        $pilotos = $query->fetchAll(PDO::FETCH_OBJ);
        return $pilotos;
    }

    public function searchPilotos($term)
    {
        $query = $this->db->prepare('
        SELECT * FROM pilotos 
        WHERE nombre LIKE ? OR apellido LIKE ?
        ORDER BY nombre ASC
    ');
        $likeTerm = "%$term%";
        $query->execute([$likeTerm, $likeTerm]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function getPiloto($id)
    {
        $Query = $this->db->prepare("SELECT * FROM pilotos WHERE piloto_id = :piloto_id");
        $Query->bindParam('piloto_id', $id);
        $Query->execute();
        return $Query->fetch(PDO::FETCH_OBJ);
    }

    public function addPiloto($nombre, $apellido, $foto = null)
    {
        $query = $this->db->prepare("INSERT INTO pilotos (nombre, apellido, foto) VALUES (:nombre, :apellido, :foto)");
        $query->bindParam(':nombre', $nombre);
        $query->bindParam(':apellido', $apellido);
        $query->bindParam(':foto', $foto);
        $query->execute();

        return $this->db->lastInsertId();
    }

    public function deletePiloto($id)
    {
        $query = $this->db->prepare("DELETE FROM pilotos WHERE piloto_id = :piloto_id");
        $query->bindParam('piloto_id', $id);
        $query->execute();
    }

    public function updatePiloto($id, $nombre, $apellido)
    {
        $query = $this->db->prepare("UPDATE pilotos SET nombre = :nombre, apellido = :apellido, foto = :foto WHERE piloto_id = :piloto_id");
        $query->bindParam('nombre', $nombre);
        $query->bindParam('apellido', $apellido);
        $query->bindParam('foto', $foto);
        $query->bindParam('piloto_id', $id);
        $query->execute();
    }
}
