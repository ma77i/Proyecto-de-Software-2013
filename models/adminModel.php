<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of adminModel
 *
 * @author MatiasBarrera
 */
class adminModel extends Model {

    //put your code 
    //
    public function __construct() {
        parent::__construct();
    }

    public function getPerfiles() {
        $query = $this->_db->prepare("select * from perfil where perfil<>? and perfil<>?");
        $data = array("administrador","ayudante");
        $query->execute($data);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function existeUsuarioIngresado($usuario) {
        $query = $this->_db->prepare("select count(*) as cantidad from usuario where usuario=?");
        $data = array($usuario);
        $query->execute($data);
        return $query->fetch();
    }

    public function existeCuitIngresado($cuit) {
        $query = $this->_db->prepare("select count(*) as cantidad from persona where cuit=?");
        $data = array($cuit);
        $query->execute($data);
        return $query->fetch();
    }

    public function getClientes() {

        $query = $this->_db->prepare("select idcliente,nombre,apellido,condimpositiva,mail,direccion,razonsocial,cuit from cliente c inner join persona p on p.idpersona=c.idpersona where estado='activo' ");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertarCliente($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $condicionimpositiva) {

        $existe = $this->existeCuitIngresado($cuit);
        if ($existe["cantidad"] == 0) {
            $query = $this->_db->prepare("INSERT INTO persona (nombre,apellido,mail,direccion,razonsocial,cuit) values (?,?,?,?,?,?)");

            $data = array($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit);
            $query->execute($data);
            $order_id = $this->_db->lastInsertId();
            $query2 = $this->_db->prepare("insert into cliente (condimpositiva,idpersona,estado) values (?,?,?)");
            $data2 = array($condicionimpositiva, $order_id, "activo");
            $query2->execute($data2);
            return true;
        } else {

            return false;
        }
    }

    public function eliminarCliente($id) {

        $query = $this->_db->prepare("UPDATE cliente set estado='inactivo' WHERE idcliente = ?");
        $data = array($id);
        $query->execute($data);
    }

    public function getDatoCliente($id) {

        $query = $this->_db->prepare("select idcliente,nombre,apellido,condimpositiva,mail,direccion,razonsocial,cuit,estado from cliente c inner join persona p on p.idpersona=c.idpersona where c.idcliente= ? ");
        $data = array($id);
        $query->execute($data);
        return $query->fetch();
    }

    public function editarCliente($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $idcliente,$condimpositiva) {
        $query = $this->_db->prepare("UPDATE persona set nombre=?,apellido=?,mail=?,direccion=?,razonsocial=?,cuit=? where idpersona=(select c.idpersona from cliente c where c.idcliente=?)");
        $data = array($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $idcliente);
        $query->execute($data);
        $query1=$this->_db->prepare("UPDATE cliente set condimpositiva=? where idcliente=?");
        $array=array($condimpositiva,$idcliente);
        $query1->execute($array);
        
    }

    public function getSocios() {
        $query = $this->_db->prepare("select s.idsocio,per.perfil,p.nombre,p.apellido,p.cuit,p.direccion,p.mail,p.razonsocial,usuario from usuario u inner join persona p on p.idpersona =u.idpersona inner join perfil per on per.idperfil=u.idperfil inner join socio s on s.idpersona=p.idpersona where s.estado=?");
        $data = array("activo");
        $query->execute($data);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDatoSocio($id) {
        $query = $this->_db->prepare("select p.nombre,p.apellido,p.cuit,p.direccion,p.mail,p.razonsocial,p.latitud,p.longitud,u.clave,u.usuario,per.idperfil,per.perfil from usuario u inner join persona p on p.idpersona =u.idpersona inner join perfil per on per.idperfil=u.idperfil inner join socio s on s.idpersona=p.idpersona  where s.idsocio=?");
        $data = array($id);
        $query->execute($data);
        return $query->fetch();
    }

    public function cargarayudantes($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $usuario, $clave, $select, $nombreayu1, $apellidoayu1, $dniayu1, $user_ayu1, $clave_ayu1, $nombreayu2, $apellidoayu2, $dniayu2, $user_ayu2, $clave_ayu2, $latitud, $longitud) {
        $perfilayudante = $this->_db->prepare("select idperfil from perfil where perfil=? ");
        $vector = array("ayudante");
        $perfilayudante->execute($vector);
        while ($payu = $perfilayudante->fetch(PDO::FETCH_ASSOC)) {
            $r2 = $payu['idperfil'];
        }
        if ($select == 1) { //selecciono la opcion 1 
            $existeUsuario = $this->existeUsuarioIngresado($user_ayu1);
            if ($existeUsuario["cantidad"] == 0) {
                //CARGO USUARIO SOCIO
                $query = $this->_db->prepare("INSERT INTO persona (nombre,apellido,mail,direccion,razonsocial,cuit,latitud,longitud) values (?,?,?,?,?,?,?,?)");
                $data = array($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $latitud, $longitud);
                $query->execute($data);
                $order_id = $this->_db->lastInsertId();
                $query2 = $this->_db->prepare("insert into socio (estado,idpersona) values (?,?)");
                $data2 = array("activo", $order_id);
                $query2->execute($data2);
                $idsocio = $this->_db->lastInsertId();
                $queryperfil = $this->_db->prepare("select idperfil from perfil where perfil=? ");
                $data3 = array("socio");
                $queryperfil->execute($data3);
                while ($row = $queryperfil->fetch(PDO::FETCH_ASSOC)) {
                    $r = $row['idperfil'];
                }


                $query4 = $this->_db->prepare("INSERT INTO usuario (usuario,clave,idperfil,idpersona) values (?,?,?,?)");
                $data4 = array($usuario, $clave, $r, $order_id);
                $query4->execute($data4);

                //CARGO AYUDANTE


                $query5 = $this->_db->prepare("INSERT INTO persona (nombre,apellido) values(?,?)");
                $data5 = array($nombreayu1, $apellidoayu1);
                $query5->execute($data5);
                $idpersona = $this->_db->lastInsertId();
                $query6 = $this->_db->prepare("insert ayudante (idpersona,dni,idsocio,estado) values(?,?,?,?)");
                $data6 = array($idpersona, $dniayu1, $idsocio, "activo");
                $query6->execute($data6);
                $query7 = $this->_db->prepare("insert into usuario (usuario,clave,idperfil,idpersona) values(?,?,?,?)");
                $data7 = array($user_ayu1, $clave_ayu1, $r2, $idpersona);
                $query7->execute($data7);
                return "exito";
            } else {
                return "erroruserUno";
            }
        } else {
            if ($select == 2) {
                $existeUsuarioayu1 = $this->existeUsuarioIngresado($user_ayu1);
                $existeUsuarioayu2 = $this->existeUsuarioIngresado($user_ayu2);
                if ($existeUsuarioayu1['cantidad'] == 0) {
                    if ($existeUsuarioayu2['cantidad'] == 0) {
                        //CARGO USUARIO SOCIO
                        $query = $this->_db->prepare("INSERT INTO persona (nombre,apellido,mail,direccion,razonsocial,cuit,latitud,longitud) values (?,?,?,?,?,?,?,?)");
                        $data = array($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $latitud, $longitud);
                        $query->execute($data);
                        $order_id = $this->_db->lastInsertId();
                        $query2 = $this->_db->prepare("insert into socio (estado,idpersona) values (?,?)");
                        $data2 = array("activo", $order_id);
                        $query2->execute($data2);
                        $idsocio = $this->_db->lastInsertId();
                        $queryperfil = $this->_db->prepare("select idperfil from perfil where perfil=?");
                        $data3 = array("socio");
                        $queryperfil->execute($data3);
                        while ($row = $queryperfil->fetch(PDO::FETCH_ASSOC)) {
                            $r = $row['idperfil'];
                        }
                        $query4 = $this->_db->prepare("INSERT INTO usuario (usuario,clave,idperfil,idpersona) values (?,?,?,?)");
                        $data4 = array($usuario, $clave, $r, $order_id);
                        $query4->execute($data4);
                        //CARGO AYUDANTE 1

                        $query8 = $this->_db->prepare("INSERT INTO persona (nombre,apellido) values(?,?)");
                        $data5 = array($nombreayu1, $apellidoayu1);
                        $query8->execute($data5);
                        $idpersona = $this->_db->lastInsertId();
                        $query9 = $this->_db->prepare("insert ayudante (idpersona,dni,idsocio,estado) values(?,?,?,?)");
                        $data8 = array($idpersona, $dniayu1, $idsocio, "activo");
                        $query9->execute($data8);
                        $query10 = $this->_db->prepare("insert into usuario (usuario,clave,idperfil,idpersona) values(?,?,?,?)");
                        $data9 = array($user_ayu1, $clave_ayu1, $r2, $idpersona);
                        $query10->execute($data9);

                        //CARGO AYUDANTE 2

                        $query11 = $this->_db->prepare("INSERT INTO persona (nombre,apellido) values(?,?)");
                        $array = array($nombreayu2, $apellidoayu2);
                        $query11->execute($array);
                        $idpersona2 = $this->_db->lastInsertId();
                        $query12 = $this->_db->prepare("insert INTO ayudante (idpersona,dni,idsocio,estado) values(?,?,?,?)");
                        $array1 = array($idpersona2, $dniayu2, $idsocio,"activo");
                        $query12->execute($array1);
                        $query13 = $this->_db->prepare("insert into usuario (usuario,clave,idperfil,idpersona) values(?,?,?,?)");
                        $array2 = array($user_ayu2, $clave_ayu2, $r2, $idpersona2);
                        $query13->execute($array2);
                        return "exito";
                    } else {
                        return "erroruserDos";
                    }
                } else {
                    return "erroruserUno";
                }
            } else {
                $query = $this->_db->prepare("INSERT INTO persona (nombre,apellido,mail,direccion,razonsocial,cuit,latitud,longitud) values (?,?,?,?,?,?,?,?)");
                $data = array($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $latitud, $longitud);
                $query->execute($data);
                $order_id = $this->_db->lastInsertId();
                $query2 = $this->_db->prepare("insert into socio (estado,idpersona) values (?,?)");
                $data2 = array("activo", $order_id);
                $query2->execute($data2);
                $idsocio = $this->_db->lastInsertId();
                $queryperfil = $this->_db->prepare("select idperfil from perfil where perfil=?");
                $data3 = array("socio");
                $queryperfil->execute($data3);
                while ($row = $queryperfil->fetch(PDO::FETCH_ASSOC)) {
                    $r = $row['idperfil'];
                }
                $query4 = $this->_db->prepare("INSERT INTO usuario (usuario,clave,idperfil,idpersona) values (?,?,?,?)");
                $data4 = array($usuario, $clave, $r, $order_id);
                $query4->execute($data4);
                return "exito";
            }
        }
    }

    public function insertarSocio($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $usuario, $clave, $select, $nombreayu1, $apellidoayu1, $dniayu1, $user_ayu1, $clave_ayu1, $nombreayu2, $apellidoayu2, $dniayu2, $user_ayu2, $clave_ayu2, $latitud, $longitud) {
        $existeUsuario = $this->existeUsuarioIngresado($usuario);
        if ($existeUsuario["cantidad"] == 0) {
            $existe = $this->existeCuitIngresado($cuit);
            if ($existe["cantidad"] == 0) { //SI NO EXISTE USUARIO
                $error = $this->cargarayudantes($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $usuario, $clave, $select, $nombreayu1, $apellidoayu1, $dniayu1, $user_ayu1, $clave_ayu1, $nombreayu2, $apellidoayu2, $dniayu2, $user_ayu2, $clave_ayu2, $latitud, $longitud);
                if ($error == "exito") { //si no existe usuario y se agrego con exito un ayudante o 2 ayudantes entonces cargo el usuario
                    return "exito";
//                 
                } else {
                    if ($error == "erroruserUno") {
                        return "erroruserUno";
                    } else {
                        return "erroruserDos";
                    }
                }
            } else {
                return "errorcuit";
            }
        } else {
            return "errorusuario";
        }
    }

    public function getDatosAyudantesSocio($idsocio) {
        $query = $this->_db->prepare("select ayu.idayudante, ayu.dni,p.nombre,p.apellido,u.usuario,u.clave from ayudante ayu inner join socio s on s.idsocio=ayu.idsocio inner join persona p on p.idpersona=ayu.idpersona inner join usuario u on u.idpersona=p.idpersona where s.idsocio=?");
        $array = array($idsocio);
        $query->execute($array);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function editarSocio($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $usuario, $clave, $perfil, $latitud, $longitud, $idAyu1, $nomAyu1, $apeAyu1, $dniAyu1, $usuarioAyu1, $claveAyu1, $idAyu2, $nomAyu2, $apeAyu2, $dniAyu2, $usuarioAyu2, $claveAyu2, $idsocio) {
        $query = $this->_db->prepare("UPDATE persona set nombre=?,apellido=?,mail=?,direccion=?,razonsocial=?,cuit=?,latitud=?,longitud=? where idpersona=(select s.idpersona from socio s where s.idsocio =?)");
        $data = array($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $latitud, $longitud, $idsocio);
        $query->execute($data);
        $queryperfil = $this->_db->prepare("select perfil from perfil where idperfil=?");
        $data2 = array($perfil);
        $queryperfil->execute($data2);
        while ($row = $queryperfil->fetch(PDO::FETCH_ASSOC)) {
            $r = $row['perfil'];
        }
        $query3 = $this->_db->prepare("update usuario set usuario=?,clave=?,idperfil=? where idpersona=(select s.idpersona from socio s where s.idsocio=?)");
        $data4 = array($usuario, $clave, $perfil, $idsocio);
        $query3->execute($data4);

        if ($idAyu1 != '') {
            $queryA = $this->_db->prepare("UPDATE ayudante  SET dni=? where idayudante=?");
            $data = array($dniAyu1, $idAyu1);
            $queryA->execute($data);
            $queryA2 = $this->_db->prepare("UPDATE persona  SET nombre=?, apellido=? where idpersona=(select idpersona from ayudante where idayudante=?)");
            $data2 = array($nomAyu1, $apeAyu1, $idAyu1);
            $queryA2->execute($data2);
            $queryA3 = $this->_db->prepare("UPDATE usuario u inner join persona p on (u.idpersona=p.idpersona) inner join ayudante a on (p.idpersona=a.idpersona) SET u.usuario=?, u.clave=? where (a.idayudante=?)");
            $data3 = array($usuarioAyu1, $claveAyu1, $idAyu1);
            $queryA3->execute($data3);
        }

        if ($idAyu2 != '') {
            $queryA = $this->_db->prepare("UPDATE ayudante  SET dni=? where idayudante=?");
            $data = array($dniAyu2, $idAyu2);
            $queryA->execute($data);
            $queryA2 = $this->_db->prepare("UPDATE persona  SET nombre=?, apellido=? where idpersona=(select idpersona from ayudante where idayudante=?)");
            $data2 = array($nomAyu2, $apeAyu2, $idAyu2);
            $queryA2->execute($data2);
            $queryA3 = $this->_db->prepare("UPDATE usuario u inner join persona p on (u.idpersona=p.idpersona) inner join ayudante a on (p.idpersona=a.idpersona) SET u.usuario=?, u.clave=? where (a.idayudante=?)");
            $data3 = array($usuarioAyu2, $claveAyu2, $idAyu2);
            $queryA3->execute($data3);
        }

        if ($r != "socio") {

            $query4 = $this->_db->prepare("insert into  inquilino (estado,idpersona) values (?,(select u.idpersona from usuario u where u.usuario=? ))");
            $data5 = array("activo", $usuario);
            $query4->execute($data5);
            $lastid = $this->_db->lastInsertId();
            $query5 = $this->_db->prepare("UPDATE ayudante SET idsocio=?,idinquilino=? WHERE idsocio=?");
            $data6 = array("null", $lastid, $idsocio);
            $query5->execute($data6);
            $query6 = $this->_db->prepare("delete from socio where idsocio=?");
            $data7 = array($idsocio);
            $query6->execute($data7);
        }
    }

    public function eliminarSocio($idsocio) {
        $query = $this->_db->prepare("SELECT COUNT(*) as cantidad FROM ayudante where idsocio=?");
        $data = array($idsocio);
        $query->execute($data);
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $r = $row['cantidad'];
        }
        if ($r == 0) {
            $query2 = $this->_db->prepare("UPDATE socio SET estado='inactivo' WHERE idsocio = ?");
            $data2 = array($idsocio);
            $query2->execute($data2);
        } else {
            $query3 = $this->_db->prepare("UPDATE socio s inner join ayudante a on(s.idsocio=a.idsocio) SET s.estado='inactivo', a.estado='inactivo' WHERE a.idsocio = ?");
            $data3 = array($idsocio);
            $query3->execute($data3);
    }
    }

    public function getInquilinos() {
        $query = $this->_db->prepare("select i.idinquilino,per.perfil,p.nombre,p.apellido,p.cuit,p.direccion,p.mail,p.razonsocial,usuario from usuario u inner join persona p on p.idpersona =u.idpersona inner join perfil per on per.idperfil=u.idperfil inner join inquilino i on i.idpersona=p.idpersona where i.estado=?");
        $data = array("activo");
        $query->execute($data);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDatoInquilino($id) {
        $query = $this->_db->prepare("select per.idperfil,p.nombre,p.apellido,p.cuit,p.direccion,p.mail,p.razonsocial, p.latitud, p.longitud ,u.clave,u.usuario,per.idperfil,per.perfil from usuario u inner join persona p on p.idpersona =u.idpersona inner join perfil per on per.idperfil=u.idperfil inner join inquilino i on i.idpersona=p.idpersona  where i.idinquilino=?");
        $data = array($id);
        $query->execute($data);
        return $query->fetch();
    }

    public function insertarInquilino($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $usuario, $clave, $select, $nombreayu1, $apellidoayu1, $dniayu1, $user_ayu1, $clave_ayu1, $nombreayu2, $apellidoayu2, $dniayu2, $user_ayu2, $clave_ayu2, $latitud, $longitud) {
        $existeUsuario = $this->existeUsuarioIngresado($usuario);
        if ($existeUsuario["cantidad"] == 0) {
            $existe = $this->existeCuitIngresado($cuit);
            if ($existe["cantidad"] == 0) {
                $error = $this->cargarayudantesinquilino($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $usuario, $clave, $select, $nombreayu1, $apellidoayu1, $dniayu1, $user_ayu1, $clave_ayu1, $nombreayu2, $apellidoayu2, $dniayu2, $user_ayu2, $clave_ayu2, $latitud, $longitud);
                if ($error == "exito") {

                    return "exito";
                } else {
                    if ($error == "erroruserUno") {
                        return "erroruserUno";
                    } else {
                        return "erroruserDos";
                    }
                }
            } else {
                return "errorcuit";
            }
        } else {
            return "errorusuario";
        }
    }

    public function cargarayudantesinquilino($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $usuario, $clave, $select, $nombreayu1, $apellidoayu1, $dniayu1, $user_ayu1, $clave_ayu1, $nombreayu2, $apellidoayu2, $dniayu2, $user_ayu2, $clave_ayu2, $latitud, $longitud) {
        $perfilayudante = $this->_db->prepare("select idperfil from perfil where perfil=? ");
        $vector = array("ayudante");
        $perfilayudante->execute($vector);
        while ($payu = $perfilayudante->fetch(PDO::FETCH_ASSOC)) {
            $r2 = $payu['idperfil'];
        }
        if ($select == 1) { //selecciono la opcion 1 
            $existeUsuario = $this->existeUsuarioIngresado($user_ayu1);
            if ($existeUsuario["cantidad"] == 0) {
                //CARGO USUARIO INQUILINO
                $query = $this->_db->prepare("INSERT INTO persona (nombre,apellido,mail,direccion,razonsocial,cuit,latitud,longitud) values (?,?,?,?,?,?,?,?)");
                $data = array($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $latitud, $longitud);
                $query->execute($data);
                $order_id = $this->_db->lastInsertId();
                $query2 = $this->_db->prepare("insert into inquilino (idpersona,estado) values (?,?)");
                $data2 = array($order_id, "activo");
                $query2->execute($data2);
                $idinquilino = $this->_db->lastInsertId();
                $queryperfil = $this->_db->prepare("select idperfil from perfil where perfil=? ");
                $data3 = array("inquilino");
                $queryperfil->execute($data3);
                while ($row = $queryperfil->fetch(PDO::FETCH_ASSOC)) {
                    $r = $row['idperfil'];
                }


                $query4 = $this->_db->prepare("INSERT INTO usuario (usuario,clave,idperfil,idpersona) values (?,?,?,?)");
                $data4 = array($usuario, $clave, $r, $order_id);
                $query4->execute($data4);

                //CARGO AYUDANTE


                $query5 = $this->_db->prepare("INSERT INTO persona (nombre,apellido) values(?,?)");
                $data5 = array($nombreayu1, $apellidoayu1);
                $query5->execute($data5);
                $idpersona = $this->_db->lastInsertId();
                $query6 = $this->_db->prepare("insert ayudante (idpersona,dni,idinquilino,estado) values(?,?,?,?)");
                $data6 = array($idpersona, $dniayu1, $idinquilino,"activo");
                $query6->execute($data6);
                $query7 = $this->_db->prepare("insert into usuario (usuario,clave,idperfil,idpersona) values(?,?,?,?)");
                $data7 = array($user_ayu1, $clave_ayu1, $r2, $idpersona);
                $query7->execute($data7);
                return "exito";
            } else {
                return "erroruserUno";
            }
        } else {
            if ($select == 2) {
                $existeUsuarioayu1 = $this->existeUsuarioIngresado($user_ayu1);
                $existeUsuarioayu2 = $this->existeUsuarioIngresado($user_ayu2);
                if ($existeUsuarioayu1['cantidad'] == 0) {
                    if ($existeUsuarioayu2['cantidad'] == 0) {
                        //CARGO USUARIO INQUILINO
                        $query = $this->_db->prepare("INSERT INTO persona (nombre,apellido,mail,direccion,razonsocial,cuit,latitud,longitud) values (?,?,?,?,?,?,?,?)");
                        $data = array($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $latitud, $longitud);
                        $query->execute($data);
                        $order_id = $this->_db->lastInsertId();
                        $query2 = $this->_db->prepare("insert into inquilino (estado,idpersona) values (?,?)");
                        $data2 = array("activo", $order_id);
                        $query2->execute($data2);
                        $idinquilino = $this->_db->lastInsertId();
                        $queryperfil = $this->_db->prepare("select idperfil from perfil where perfil=?");
                        $data3 = array("inquilino");
                        $queryperfil->execute($data3);
                        while ($row = $queryperfil->fetch(PDO::FETCH_ASSOC)) {
                            $r = $row['idperfil'];
                        }
                        $query4 = $this->_db->prepare("INSERT INTO usuario (usuario,clave,idperfil,idpersona) values (?,?,?,?)");
                        $data4 = array($usuario, $clave, $r, $order_id);
                        $query4->execute($data4);
                        //CARGO AYUDANTE 1

                        $query8 = $this->_db->prepare("INSERT INTO persona (nombre,apellido) values(?,?)");
                        $data5 = array($nombreayu1, $apellidoayu1);
                        $query8->execute($data5);
                        $idpersona = $this->_db->lastInsertId();
                        $query9 = $this->_db->prepare("insert ayudante (idpersona,dni,idinquilino,estado) values(?,?,?,?)");
                        $data8 = array($idpersona, $dniayu1, $idinquilino,"activo");
                        $query9->execute($data8);
                        $query10 = $this->_db->prepare("insert into usuario (usuario,clave,idperfil,idpersona) values(?,?,?,?)");
                        $data9 = array($user_ayu1, $clave_ayu1, $r2, $idpersona);
                        $query10->execute($data9);

                        //CARGO AYUDANTE 2

                        $query11 = $this->_db->prepare("INSERT INTO persona (nombre,apellido) values(?,?)");
                        $array = array($nombreayu2, $apellidoayu2);
                        $query11->execute($array);
                        $idpersona2 = $this->_db->lastInsertId();
                        $query12 = $this->_db->prepare("insert INTO ayudante (idpersona,dni,idinquilino,estado) values(?,?,?,?)");
                        $array1 = array($idpersona2, $dniayu2, $idinquilino,"activo");
                        $query12->execute($array1);
                        $query13 = $this->_db->prepare("insert into usuario (usuario,clave,idperfil,idpersona) values(?,?,?,?)");
                        $array2 = array($user_ayu2, $clave_ayu2, $r2, $idpersona2);
                        $query13->execute($array2);
                        return "exito";
                    } else {
                        return "erroruserDos";
                    }
                } else {
                    return "erroruserUno";
                }
            } else {
                $query = $this->_db->prepare("INSERT INTO persona (nombre,apellido,mail,direccion,razonsocial,cuit,latitud,longitud) values (?,?,?,?,?,?,?,?)");
                $data = array($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $latitud, $longitud);
                $query->execute($data);
                $order_id = $this->_db->lastInsertId();
                $query2 = $this->_db->prepare("insert into inquilino (estado,idpersona) values (?,?)");
                $data2 = array("activo", $order_id);
                $query2->execute($data2);
                $idinquilino = $this->_db->lastInsertId();
                $queryperfil = $this->_db->prepare("select idperfil from perfil where perfil=?");
                $data3 = array("inquilino");
                $queryperfil->execute($data3);
                while ($row = $queryperfil->fetch(PDO::FETCH_ASSOC)) {
                    $r = $row['idperfil'];
                }
                $query4 = $this->_db->prepare("INSERT INTO usuario (usuario,clave,idperfil,idpersona) values (?,?,?,?)");
                $data4 = array($usuario, $clave, $r, $order_id);
                $query4->execute($data4);
                return "exito";
            }
        }
    }

    public function getDatosAyudantesInquilino($idinquilino) {
        $query = $this->_db->prepare("select ayu.idayudante, ayu.dni,p.nombre,p.apellido,u.usuario,u.clave from ayudante ayu inner join inquilino i on i.idinquilino=ayu.idinquilino inner join persona p on p.idpersona=ayu.idpersona inner join usuario u on u.idpersona=p.idpersona where i.idinquilino=?");
        $array = array($idinquilino);
        $query->execute($array);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function editarInquilino($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $usuario, $clave, $perfil, $latitud, $longitud, $idAyu1, $nomAyu1, $apeAyu1, $dniAyu1, $usuarioAyu1, $claveAyu1, $idAyu2, $nomAyu2, $apeAyu2, $dniAyu2, $usuarioAyu2, $claveAyu2, $idinquilino) {
        $query = $this->_db->prepare("UPDATE persona set nombre=?,apellido=?,mail=?,direccion=?,razonsocial=?,cuit=?,latitud=?,longitud=? where idpersona=(select i.idpersona from inquilino i where i.idinquilino =?)");
        $data = array($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $latitud, $longitud, $idinquilino);
        $query->execute($data);
        $query3 = $this->_db->prepare("update usuario set usuario=?,clave=?,idperfil=? where idpersona=(select i.idpersona from inquilino i where i.idinquilino=?)");
        $data4 = array($usuario, $clave, $perfil, $idinquilino);
        $query3->execute($data4);
        $query4 = $this->_db->prepare("select perfil from perfil where idperfil=?");
        $data2 = array($perfil);
        $query4->execute($data2);
        while ($row = $query4->fetch(PDO::FETCH_ASSOC)) {
            $r = $row['perfil'];
        }

        if ($idAyu1 != '') {
            $queryA = $this->_db->prepare("UPDATE ayudante  SET dni=? where idayudante=?");
            $data = array($dniAyu1, $idAyu1);
            $queryA->execute($data);
            $queryA2 = $this->_db->prepare("UPDATE persona  SET nombre=?, apellido=? where idpersona=(select idpersona from ayudante where idayudante=?)");
            $data2 = array($nomAyu1, $apeAyu1, $idAyu1);
            $queryA2->execute($data2);
            $queryA3 = $this->_db->prepare("UPDATE usuario u inner join persona p on (u.idpersona=p.idpersona) inner join ayudante a on (p.idpersona=a.idpersona) SET u.usuario=?, u.clave=? where (a.idayudante=?)");
            $data3 = array($usuarioAyu1, $claveAyu1, $idAyu1);
            $queryA3->execute($data3);
        }

        if ($idAyu2 != '') {
            $queryA = $this->_db->prepare("UPDATE ayudante  SET dni=? where idayudante=?");
            $data = array($dniAyu2, $idAyu2);
            $queryA->execute($data);
            $queryA2 = $this->_db->prepare("UPDATE persona  SET nombre=?, apellido=? where idpersona=(select idpersona from ayudante where idayudante=?)");
            $data2 = array($nomAyu2, $apeAyu2, $idAyu2);
            $queryA2->execute($data2);
            $queryA3 = $this->_db->prepare("UPDATE usuario u inner join persona p on (u.idpersona=p.idpersona) inner join ayudante a on (p.idpersona=a.idpersona) SET u.usuario=?, u.clave=? where (a.idayudante=?)");
            $data3 = array($usuarioAyu2, $claveAyu2, $idAyu2);
            $queryA3->execute($data3);
        }
        if ($r != "inquilino") {

            $query4 = $this->_db->prepare("insert into  socio (estado,idpersona) values (?,(select u.idpersona from usuario u where u.usuario=? ))");
            $data5 = array("activo", $usuario);
            $query4->execute($data5);
            $lastid = $this->_db->lastInsertId();
            $query5 = $this->_db->prepare("UPDATE ayudante SET idsocio=?,idinquilino=? WHERE idinquilino=?");
            $data6 = array($lastid, "null", $idinquilino);
            $query5->execute($data6);
            $query6 = $this->_db->prepare("delete from inquilino where idinquilino=?");
            $data7 = array($idinquilino);
            $query6->execute($data7);
        }
    }

    public function eliminarInquilino($idinquilino) {
        $query = $this->_db->prepare("SELECT COUNT(*) as cantidad FROM ayudante where idinquilino=?");
        $data = array($idinquilino);
        $query->execute($data);
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $r = $row['cantidad'];
        }
        if ($r == 0) {
            $query2 = $this->_db->prepare("UPDATE inquilino SET estado='inactivo' WHERE idinquilino = ?");
            $data2 = array($idinquilino);
            $query2->execute($data2);
        } else {
            $query3 = $this->_db->prepare("UPDATE inquilino i inner join ayudante a on(i.idinquilino=a.idinquilino) SET i.estado='inactivo', a.estado='inactivo' WHERE a.idinquilino = ?");
            $data3 = array($idinquilino);
            $query3->execute($data3);
        }
    }

    public function getEspecies() {
        
        $query = $this->_db->prepare("select e.idespecie,p.idplanta,p.nombre as planta ,p.nombrecientifico,p.habitat,p.region,p.epocaaño,c.color,e.nombre as especie from planta p inner join especie e on e.idespecie=p.idespecie inner join color c on c.idcolor=p.idcolor where estado='activo'");
        $v = array();
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function especiesCargadas() {
        $query = $this->_db->prepare("select * from especie");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertarEspecieSeleccionandoCombo($planta, $nombrecientifico, $habitat, $region, $epoca, $seleccion_color, $seleccion_especie,$precio) {
        $query1 = $this->_db->prepare("insert into planta (nombre,nombrecientifico,habitat,region,epocaaño,idcolor,idespecie,estado,precio) values (?,?,?,?,?,?,?,?,?)");
        $array1 = array($planta, $nombrecientifico, $habitat, $region, $epoca, $seleccion_color, $seleccion_especie, "activo",$precio);
        $query1->execute($array1);
    }

    public function insertarEspecieIngresandoInput($planta, $nombrecientifico, $habitat, $region, $epoca, $inputColor, $inputEspecie, $precio) {
        $query = $this->_db->prepare("insert into especie (nombre) values(?)");
        $array = array($inputEspecie);
        $query->execute($array);
        $last_id_especie = $this->_db->lastInsertId();
        $color = $this->_db->prepare("insert into color (color) values (?)");
        $array1 = array($inputColor);
        $color->execute($array1);
        $last_id_color = $this->_db->lastInsertId();
        $query2 = $this->_db->prepare("insert into planta (nombre,nombrecientifico,habitat,region,epocaaño,idcolor,idespecie,estado, precio) values (?,?,?,?,?,?,?,?,?)");
        $array2 = array($planta, $nombrecientifico, $habitat, $region, $epoca, $last_id_color, $last_id_especie, "activo", $precio);
        $query2->execute($array2);
    }

    public function insertarEspecieIngresandoInputColorConSeleccionComboEspecie($planta, $nombrecientifico, $habitat, $region, $epoca, $input_color, $seleccion_especie, $precio) {
        $color = $this->_db->prepare("insert into color (color) values (?)");
        $array1 = array($input_color);
        $color->execute($array1);
        $last_id_color = $this->_db->lastInsertId();
        $query = $this->_db->prepare("insert into planta (nombre,nombrecientifico,habitat,region,epocaaño,idcolor,idespecie,estado,precio) values (?,?,?,?,?,?,?,?,?)");
        $array = array($planta, $nombrecientifico, $habitat, $region, $epoca, $last_id_color, $seleccion_especie, "activo", $precio);
        $query->execute($array);
    }

    public function insertarEspecieIngresandoInputEspecieConSeleccionComboColor($planta, $nombrecientifico, $habitat, $region, $epoca, $input_especie, $seleccion_color, $precio) {
        $query = $this->_db->prepare("insert into especie (nombre) values(?)");
        $array = array($input_especie);
        $query->execute($array);
        $last_id_especie = $this->_db->lastInsertId();
        $query2 = $this->_db->prepare("insert into planta (nombre,nombrecientifico,habitat,region,epocaaño,idcolor,idespecie,estado, precio) values (?,?,?,?,?,?,?,?,?)");
        $array1 = array($planta, $nombrecientifico, $habitat, $region, $epoca, $seleccion_color, $last_id_especie, "activo", $precio);
        $query2->execute($array1);
    }

    public function getDatoEspecie($idplanta) {
        $query = $this->_db->prepare("select e.idespecie,c.idcolor,p.idplanta,p.nombre as planta ,p.nombrecientifico,p.habitat,p.region,p.epocaaño,c.color,e.nombre from planta p inner join especie e on e.idespecie=p.idespecie inner join color c on c.idcolor=p.idcolor where p.idplanta=?");
        $array = array($idplanta);
        $query->execute($array);
        return $query->fetch();
    }

    public function editarEspecie($nombre, $nombrec, $habitat, $comboespecies, $region, $epoca, $combocolores, $idplanta) {

        $query4 = $this->_db->prepare("update  planta set nombre=? ,nombrecientifico=?,habitat=?,region=?,epocaaño=?,idcolor=?,idespecie=? where idplanta=?");
        $data4 = array($nombre, $nombrec, $habitat, $region, $epoca, $combocolores, $comboespecies, $idplanta);
        $query4->execute($data4);
    }

    public function eliminarEspecie($idplanta) {
        $query1 = $this->_db->prepare("update planta set estado=? where idplanta=?");
        $array = array("inactivo", $idplanta);
        $query1->execute($array);
    }

    public function getColores() {
        $query = $this->_db->prepare("select * from color");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
   public function validarIngresoColor($input_color){
   $query=$this->_db->prepare("select count(*) as cantidad  from color where color=?"); 
   $v=array($input_color);
   $query->execute($v);
  return $query->fetch();
    
    
   }
   public function prueba(){
    
   $query=$this->_db->prepare("select latitud,longitud from persona where idpersona=81");   
   $query->execute();
   return $query->fetch();
   }
   
   
   
}

//end de la class
