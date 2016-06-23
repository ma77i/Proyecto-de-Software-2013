<?php

class indexModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function listarFlores () {

        $query = $this->_db->prepare("SELECT p.nombre AS Planta, e.nombre AS Especie, c.color AS Color, SUM( m.cantidad ) AS Stock
                                     FROM movimientos m
                                     INNER JOIN planta p ON ( m.idplanta = p.idplanta )
                                     INNER JOIN especie e ON ( m.idespecie = e.idespecie )
                                     INNER JOIN color c ON ( m.idcolor = c.idcolor )
                                     GROUP BY m.idplanta, m.idespecie, m.idcolor");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
        
    }

}
?>
