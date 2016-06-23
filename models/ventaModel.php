<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ventaModel
 *
 * @author EZEQUIEL
 */
class ventaModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function listarEspecies() {
        $query = $this->_db->prepare("SELECT * FROM especie");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPlantasPorEspecie($idespecie) {
        $query = $this->_db->prepare("SELECT * FROM planta where idespecie = :especie");
        $query->execute(array(':especie' => $idespecie));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarColoresPlantas() {

        $query = $this->_db->prepare("SELECT * FROM color");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarMercaderiasDisponibles($idusuario, $perfiluser) {

        if ($perfiluser == "socio") {
            $query = $this->_db->prepare("SELECT i.idusuario FROM ayudante ayu
                INNER JOIN socio s ON s.idsocio = ayu.idsocio
                INNER JOIN persona p ON p.idpersona = ayu.idpersona
                INNER JOIN usuario u ON u.idpersona = p.idpersona
                WHERE s.idsocio = (SELECT so.idsocio FROM socio so
                    INNER JOIN persona pe ON pe.idpersona = so.idpersona
                    INNER JOIN usuario us ON us.idpersona = pe.idpersona
                    WHERE us.idusuario =? ) ");
            $array = array($idusuario);
            $query->execute($array);
            $ayudantes = $query->fetchAll(PDO::FETCH_ASSOC);
        } else if ($perfiluser == "inquilino") {
            $query = $this->_db->prepare("SELECT u.idusuario FROM ayudante ayu
                INNER JOIN inquilino i ON i.idinquilino = ayu.idinquilino
                INNER JOIN persona p ON p.idpersona = ayu.idpersona
                INNER JOIN usuario u ON u.idpersona = p.idpersona
                WHERE i.idinquilino = (SELECT inq.idinquilino FROM inquilino inq
                    INNER JOIN persona pe ON pe.idpersona = inq.idpersona
                    INNER JOIN usuario us ON us.idpersona = pe.idpersona
                    WHERE us.idusuario =? ) ");
            $array = array($idusuario);
            $query->execute($array);
            $ayudantes = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        $variable = array();
        array_push($variable, $idusuario);
        foreach ($ayudantes as $act) {
            array_push($variable, $act['idusuario']);
        }
        $place_holders = implode(',', array_fill(0, count($variable), '?'));

        $query2 = $this->_db->prepare("SELECT m.idplanta, m.idespecie, m.idcolor, p.nombre as Planta, p.precio as Precio, e.nombre as Especie, c.color as Color, SUM( m.cantidad ) as Stock 
                                      FROM movimientos m
                                      INNER JOIN planta p on (m.idplanta=p.idplanta)
                                      INNER JOIN especie e on (m.idespecie=e.idespecie)
                                      INNER JOIN color c on (m.idcolor=c.idcolor)
                                      WHERE m.idusuario
                                      IN ($place_holders) 
                                      GROUP BY m.idplanta, m.idespecie, m.idcolor, Planta, Especie, Color");
        $query2->execute($variable);
        return $query2->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarClientes() {
        $query = $this->_db->prepare("SELECT c.idcliente, pe.nombre, pe.apellido FROM cliente c
                                    INNER JOIN persona pe on (c.idpersona=pe.idpersona)");                      
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function venderPlanta($idplanta, $idespecie, $idcolor, $cvendida, $monto, $idcliente, $idusuario) {

        $query = $this->_db->prepare("INSERT INTO movimientos (fecha, hora, idplanta,idespecie,idcolor,cantidad,monto,tipo,idcliente,idusuario) values (:fecha,:hora,:planta,:especie,:color,:cantidad,:monto,:tipo,:idcliente,:usuario)");
        $query->execute(array(
            ':fecha' => date('Y-m-d'),
            ':hora' => date('H:i:s'),
            ':planta' => $idplanta,
            ':especie' => $idespecie,
            ':color' => $idcolor,
            ':cantidad' => -1 * abs($cvendida),
            ':monto' => $monto,
            ':tipo' => 'venta',
            ':idcliente' => $idcliente,
            ':usuario' => $idusuario));
    }

    public function listarSociosInquilinos() {
        $query = $this->_db->prepare("SELECT u.idusuario, pe.nombre, pe.apellido, u.usuario, p.perfil FROM usuario u
                                    INNER JOIN persona pe on (u.idpersona=pe.idpersona)
                                    INNER JOIN perfil p on (u.idperfil=p.idperfil)
                                    WHERE (p.perfil='socio' OR p.perfil='inquilino')");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

}
