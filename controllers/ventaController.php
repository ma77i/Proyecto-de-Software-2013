<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ventaController
 *
 * @author EZEQUIEL
 */
class ventaController extends Controller {

    private $_venta;

    public function __construct() {
        parent::__construct();
        $this->_venta = $this->loadModel("venta");
    }

    public function index($idusuario = false, $perfil = false) {
     if (!Session::get('autenticado')) {   
         
           $this->redireccionar();
     }
     else if (Session::get('tipoperfil') == "ayudante") {
     
         $this->redireccionar("ayudante");
         
         
     }
     else{
         
        $datos = $this->listarMercaderiaDisponible($idusuario, $perfil);
        $this->_view->parametrosVista['idsocioinquilino'] = $idusuario;
        $this->_view->parametrosVista['perfilsocioinquilino'] = $perfil;
        $this->_view->parametrosVista['listadomercaderia'] = $datos;
        $this->_view->parametrosVista['listadoclientes']=  $this->listarClientes ();
        $this->_view->renderizar('venta', 'venta');
        
        }
     
   
    }

    public function asignarVenta() {
     if (!Session::get('autenticado')) {   
         $this->redireccionar();
     }  
        if (Session::get('tipoperfil') == "administrador") {
            $datos = $this->listarSociosInquilinos();
            $this->_view->parametrosVista['listadosociosinquilinos'] = $datos;
            $this->_view->renderizar('asignarventa', 'asignarventa');
        }
    }

    private function listarMercaderiaDisponible($idusuario, $perfil) {

        if ($idusuario == false and $perfil == false) {
            return $this->_venta->listarMercaderiasDisponibles(Session::get('idusuario'), Session::get('tipoperfil'));
        } else {
            return $this->_venta->listarMercaderiasDisponibles($idusuario, $perfil);
        }
    }

    private function listarSociosInquilinos() {
        return $this->_venta->listarSociosInquilinos();
    }
    
    private function listarClientes() {
        return $this->_venta->listarClientes();
    }

    public function venderPlantaAdmin($idplanta, $idespecie, $idcolor, $idusuario = false, $perfil = false, $cvendida, $monto,$cliente) {
        $this->_venta->venderPlanta($idplanta, $idespecie, $idcolor, $cvendida, $monto, $cliente, $idusuario);
        $this->redireccionar('venta/index/' . $idusuario . '/' . $perfil);
    }

    public function venderPlantaSocioInquilino($idplanta, $idespecie, $idcolor, $cvendida, $monto,$cliente) {
        $this->_venta->venderPlanta($idplanta, $idespecie, $idcolor, $cvendida, $monto, $cliente, Session::get('idusuario'));
        $this->redireccionar('venta');
    }

}