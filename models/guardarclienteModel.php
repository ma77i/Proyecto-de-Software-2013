<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of guardarCliente
 *
 * @author MatiasBarrera
 */
class guardarclienteModel extends Model {

    //put your code here

    public function __construct() {
        parent::__construct();
    }

    public function getUsuario() {
        $usuario = "matias";
        return $usuario;
    }

}

?>
