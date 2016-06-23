<?php

class loginModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getUser($usuario, $password) {

        $query = $this->_db->prepare("SELECT * FROM usuario NATURAL JOIN perfil WHERE usuario = ? AND clave = ?");
        $query->execute(array($usuario, $password));
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function esActivo($idusuario, $perfil) {

        if (($perfil == 'socio') or ($perfil == 'inquilino') or ($perfil == 'ayudante')) {
            $query = "SELECT * FROM usuario NATURAL JOIN persona NATURAL JOIN $perfil WHERE estado =  'activo' and idusuario= $idusuario";
            $resultado = $this->_db->query($query);
            return $resultado->fetch(PDO::FETCH_ASSOC);
        } else
            return true;
    }

}
