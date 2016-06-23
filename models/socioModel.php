<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of socioModel
 *
 * @author MatiasBarrera
 */
class socioModel extends Model {
   public function __construct() {
        parent::__construct();
    }
    public function getDatoSocio($id){
     $query=$this->_db->prepare("select p.nombre,p.apellido,p.cuit,p.direccion,p.mail,p.razonsocial from usuario u inner join persona p on p.idpersona =u.idpersona inner join perfil per on per.idperfil=u.idperfil inner join socio s on s.idpersona=p.idpersona  where u.idusuario=?");
     $data=array($id);
     $query->execute($data);
    return $query->fetch();  
      
   
   }
    public function editarSocio($nombre,$apellido,$mail,$direccion,$razonsocial,$cuit,$idusuario){
        
    $query=$this->_db->prepare ("UPDATE persona set nombre=?,apellido=?,mail=?,direccion=?,razonsocial=?,cuit=? where idpersona=(select u.idpersona from usuario u where u.idusuario=?)");  
    $data=array($nombre,$apellido,$mail,$direccion,$razonsocial,$cuit,$idusuario);
     $query->execute($data);
     
   }
    
}
