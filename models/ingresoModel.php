<?php

class ingresoModel extends Model {

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

    public function ingresar1Planta($idespecie, $idplanta, $idcolor, $cantidad, $idusuario) {
        $query = $this->_db->prepare("INSERT INTO movimientos (fecha, hora, idplanta,idespecie,idcolor,cantidad,tipo,idusuario) values (:fecha,:hora,:planta,:especie,:color,:cantidad,:tipo,:usuario)");
        $query->execute(array(
            ':fecha' => date('Y-m-d'),
            ':hora' => date('H:i:s'),
            ':planta' => $idplanta,
            ':especie' => $idespecie,
            ':color' => $idcolor,
            ':cantidad' => $cantidad,
            ':tipo' => 'ingreso',
            ':usuario' => $idusuario));
    }

    public function ingresar2Plantas($idespecie, $idplanta, $idcolor, $cantidad, $idespecie2, $idplanta2, $idcolor2, $cantidad2, $idusuario) {
        $query = $this->_db->prepare("INSERT INTO movimientos (fecha, hora, idplanta,idespecie,idcolor,cantidad,tipo,idusuario) values (:fecha,:hora,:planta,:especie,:color,:cantidad,:tipo,:usuario)");
        $query->execute(array(
            ':fecha' => date('Y-m-d'),
            ':hora' => date('H:i:s'),
            ':planta' => $idplanta,
            ':especie' => $idespecie,
            ':color' => $idcolor,
            ':cantidad' => $cantidad,
            ':tipo' => 'ingreso',
            ':usuario' => $idusuario));

        $query2 = $this->_db->prepare("INSERT INTO movimientos (fecha, hora, idplanta,idespecie,idcolor,cantidad,tipo,idusuario) values (:fecha,:hora,:planta,:especie,:color,:cantidad,:tipo,:usuario)");
        $query2->execute(array(
            ':fecha' => date('Y-m-d'),
            ':hora' => date('H:i:s'),
            ':planta' => $idplanta2,
            ':especie' => $idespecie2,
            ':color' => $idcolor2,
            ':cantidad' => $cantidad2,
            ':tipo' => 'ingreso',
            ':usuario' => $idusuario));
    }
    
    public function ingresar3Plantas($idespecie, $idplanta, $idcolor, $cantidad, $idespecie2, $idplanta2, $idcolor2, $cantidad2, $idespecie3, $idplanta3, $idcolor3, $cantidad3, $idusuario) {
        $query = $this->_db->prepare("INSERT INTO movimientos (fecha, hora, idplanta,idespecie,idcolor,cantidad,tipo,idusuario) values (:fecha,:hora,:planta,:especie,:color,:cantidad,:tipo,:usuario)");
        $query->execute(array(
            ':fecha' => date('Y-m-d'),
            ':hora' => date('H:i:s'),
            ':planta' => $idplanta,
            ':especie' => $idespecie,
            ':color' => $idcolor,
            ':cantidad' => $cantidad,
            ':tipo' => 'ingreso',
            ':usuario' => $idusuario));

        $query2 = $this->_db->prepare("INSERT INTO movimientos (fecha, hora, idplanta,idespecie,idcolor,cantidad,tipo,idusuario) values (:fecha,:hora,:planta,:especie,:color,:cantidad,:tipo,:usuario)");
        $query2->execute(array(
            ':fecha' => date('Y-m-d'),
            ':hora' => date('H:i:s'),
            ':planta' => $idplanta2,
            ':especie' => $idespecie2,
            ':color' => $idcolor2,
            ':cantidad' => $cantidad2,
            ':tipo' => 'ingreso',
            ':usuario' => $idusuario));
    
    
        $query3 = $this->_db->prepare("INSERT INTO movimientos (fecha, hora, idplanta,idespecie,idcolor,cantidad,tipo,idusuario) values (:fecha,:hora,:planta,:especie,:color,:cantidad,:tipo,:usuario)");
        $query3->execute(array(
            ':fecha' => date('Y-m-d'),
            ':hora' => date('H:i:s'),
            ':planta' => $idplanta3,
            ':especie' => $idespecie3,
            ':color' => $idcolor3,
            ':cantidad' => $cantidad3,
            ':tipo' => 'ingreso',
            ':usuario' => $idusuario));
    }
    

    public function historialIngresos($idusuario) {

        $query = $this->_db->prepare("SELECT m.idmovimiento as idIngreso, m.fecha as Fecha, m.hora as Hora, p.nombre as Planta, e.nombre as Especie, c.color as Color, m.cantidad as Cantidad FROM movimientos m inner join planta p on (m.idplanta=p.idplanta) inner join especie e on (m.idespecie=e.idespecie) inner join color c on (m.idcolor=c.idcolor) where (m.idusuario=? and m.tipo=?)");
        $query->execute(array($idusuario, 'ingreso'));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function historialTotalIngresos($idusuario, $perfil) {

        $query = $this->_db->prepare("SELECT SUM( cantidad ) AS ingresosS
            FROM movimientos m
            INNER JOIN usuario u ON ( m.idusuario = u.idusuario ) 
            INNER JOIN perfil p ON ( u.idperfil = p.idperfil ) 
            WHERE (perfil =? AND u.idusuario =? AND m.tipo=?)");
        $query->execute(array($perfil, $idusuario, 'ingreso'));
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $r = $row['ingresosS'];
        }
        return $r;
    }

    public function historialTotalIngresosAyudantesSocio($idusuario) {

        $query = $this->_db->prepare("SELECT idsocio from socio s inner join persona p on (s.idpersona=p.idpersona) inner join usuario u on (p.idpersona=u.idpersona) where (u.idusuario=?)");
        $query->execute(array($idusuario));
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $r = $row['idsocio'];
        }
        $query2 = $this->_db->prepare("SELECT SUM(cantidad) as ingresosA from movimientos m inner join usuario u on (m.idusuario=u.idusuario) inner join perfil p on (u.idperfil=p.idperfil) where perfil='ayudante' AND m.tipo='ingreso' AND m.idusuario IN (SELECT us.idusuario FROM usuario us inner join persona pe on (us.idpersona=pe.idpersona) inner join ayudante a on (pe.idpersona=a.idpersona) where idsocio=?)");
        $query2->execute(array($r));
        while ($qrow = $query2->fetch(PDO::FETCH_ASSOC)) {
            $q = $qrow['ingresosA'];
        }
        return $q;
    }

    public function historialTotalIngresosAyudantesInquilino($idusuario) {

        $query = $this->_db->prepare("SELECT idinquilino from inquilino i inner join persona p on (i.idpersona=p.idpersona) inner join usuario u on (p.idpersona=u.idpersona) where (u.idusuario=?)");
        $query->execute(array($idusuario));
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $r = $row['idinquilino'];
        }
        $query2 = $this->_db->prepare("SELECT SUM(cantidad) as ingresosA from movimientos m inner join usuario u on (m.idusuario=u.idusuario) inner join perfil p on (u.idperfil=p.idperfil) where perfil='ayudante' AND m.tipo='ingreso' AND m.idusuario IN (SELECT us.idusuario FROM usuario us inner join persona pe on (us.idpersona=pe.idpersona) inner join ayudante a on (pe.idpersona=a.idpersona) where idinquilino=?)");
        $query2->execute(array($r));
        while ($qrow = $query2->fetch(PDO::FETCH_ASSOC)) {
            $q = $qrow['ingresosA'];
        }
        return $q;
    }

    public function getDatoIngreso($idingreso) {

        $query = $this->_db->prepare("SELECT cantidad FROM movimientos WHERE idmovimiento=?");
        $data = array($idingreso);
        $query->execute($data);
        return $query->fetch();
    }

    public function editarIngreso($cantidad, $idingreso) {

        $query = $this->_db->prepare("UPDATE movimientos SET cantidad=? WHERE idmovimiento=?");
        $data = array($cantidad, $idingreso);
        $query->execute($data);
    }

    public function eliminarIngreso($idingreso) {
        $query = $this->_db->prepare("DELETE FROM movimientos WHERE idmovimiento = ?");
        $data = array($idingreso);
        $query->execute($data);
    }

}
