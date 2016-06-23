<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class inquilinoModel extends Model {

    //put your code 
    //
    public function __construct() {
        parent::__construct();
    }

    public function getDatoInquilino($id) {
        $query = $this->_db->prepare("select p.nombre,p.apellido,p.cuit,p.direccion,p.mail,p.razonsocial from usuario u inner join persona p on p.idpersona =u.idpersona inner join perfil per on per.idperfil=u.idperfil inner join inquilino i on i.idpersona=p.idpersona  where u.idusuario=?");
        $data = array($id);
        $query->execute($data);
        return $query->fetch();
    }

    public function editarInquilino($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $idusuario) {
        $query = $this->_db->prepare("UPDATE persona set nombre=?,apellido=?,mail=?,direccion=?,razonsocial=?,cuit=? where idpersona=(select u.idpersona from usuario u where u.idusuario=?)");
        $data = array($nombre, $apellido, $mail, $direccion, $razonsocial, $cuit, $idusuario);
        $query->execute($data);
    }

}
