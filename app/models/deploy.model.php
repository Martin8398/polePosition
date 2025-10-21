<?php

require_once './config.php';

class ModelDeploy {
    
    protected $db;

    public function __construct() {
        try {
            $this->db = new PDO(
                "mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DB . ";charset=utf8mb4",
                MYSQL_USER,
                MYSQL_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $this->_deploy();
        } catch (PDOException $e) {
            die("Error al conectar a la base de datos: " . $e->getMessage());
        }
    }

    private function _deploy() {
        // Verificar si ya existen tablas
        $query = $this->db->query('SHOW TABLES');
        $tables = $query->fetchAll(PDO::FETCH_COLUMN);

        if (count($tables) === 0) {
            $sql = <<<'SQL'
-- Tablas y datos de phpMyAdmin
CREATE TABLE `carreras` (
  `carrera_id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `vueltas` int(11) NOT NULL,
  PRIMARY KEY (`carrera_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `carreras` (`carrera_id`, `fecha`, `vueltas`) VALUES
(8, '2025-10-24', 25),
(9, '2025-11-02', 56),
(10, '2025-10-30', 24);

CREATE TABLE `pilotos` (
  `piloto_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`piloto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `pilotos` (`piloto_id`, `nombre`, `apellido`, `foto`) VALUES
(1, 'Agustin', 'Ciantini', NULL),
(21, 'Martin', 'Larrosa', '68f6b5740f759_Screenshot 2025-10-20 191901.jpg'),
(22, 'Juan', 'Riestra', '68f6b71f4308d_piloto4.jpg'),
(23, 'Alan', 'Gomez', '68f6b7b0eeda2_piloto5.jpg'),
(24, 'Lucio', 'Lopez', '68f6b97bb3b36_piloto3.jpg');

CREATE TABLE `resultados` (
  `resultado_id` int(11) NOT NULL AUTO_INCREMENT,
  `piloto_id` int(11) NOT NULL,
  `carrera_id` int(11) NOT NULL,
  `posicion` int(11) NOT NULL,
  `tiempo` time NOT NULL,
  PRIMARY KEY (`resultado_id`),
  KEY `fk_resultados_carrera` (`carrera_id`),
  KEY `fk_piloto_id` (`piloto_id`),
  CONSTRAINT `fk_resultados_carrera` FOREIGN KEY (`carrera_id`) REFERENCES `carreras` (`carrera_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_resultados_piloto` FOREIGN KEY (`piloto_id`) REFERENCES `pilotos` (`piloto_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `resultados` (`resultado_id`, `piloto_id`, `carrera_id`, `posicion`, `tiempo`) VALUES
(13, 21, 8, 1, '01:42:30'),
(14, 1, 8, 2, '01:42:46'),
(15, 24, 9, 1, '01:56:54'),
(16, 1, 9, 2, '01:56:50'),
(17, 23, 9, 3, '01:59:20'),
(18, 22, 9, 4, '02:15:24'),
(19, 1, 10, 1, '01:24:34'),
(20, 21, 10, 2, '01:25:32'),
(21, 24, 10, 3, '01:26:45'),
(22, 23, 10, 4, '01:30:20');

CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  PRIMARY KEY (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `usuarios` (`usuario_id`, `username`, `pass`) VALUES
(3, 'webadmin', '$2y$10$MpaqutvRqh1Pub4IIERIIeJiFaFGpKwlclvjd.zHfUKhNsuxkdhlu');
SQL;

            try {
                $this->db->exec($sql);
                echo "<p style='color:green;'> Base de datos desplegada correctamente.</p>";
            } catch (PDOException $e) {
                echo "<p style='color:red;'> Error al desplegar la base de datos: " . $e->getMessage() . "</p>";
            }
        }
    }
}

