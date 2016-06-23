<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ayudanteController
 *
 * @author EZEQUIEL
 */
class ayudanteController extends Controller {

    private $ayudante;

    public function __construct() {
        parent::__construct();
        $this->ayudante = $this->loadModel('ayudante');
        $this->ingreso = $this->loadModel('ingreso');
    }

    public function index() {
        $this->_view->parametrosVista ['ingresosAyudante'] = $this->ingreso->historialIngresos(Session::get('idusuario'));
        $this->_view->parametrosVista ['TotalIngresosAyudante'] = $this->ingreso->historialTotalIngresos(Session::get('idusuario'), Session::get('tipoperfil'));
        $this->_view->renderizar('ayudante','ayudante');
    }

    public function editarayudante() {

        if (!Session::get('autenticado')) {
            $this->redireccionar();
        }
        if (Session::get('tipoperfil') == "ayudante") {


            $datosayudante = $this->ayudante->getDatoAyudante(Session::get('idusuario'));
            if ($this->getNum('guardar') == 1) {

                $this->ayudante->editarAyudante($this->getTexto('nombre'), $this->getTexto('apellido'), $this->getTexto("correo"), $this->getTexto("domicilio"), $this->getTexto('rsocial'), $this->getTexto('cuit'), Session::get('idusuario'));
                $this->_view->parametrosVista['mensaje'] = "Sus datos han sido modificados";
                $this->_view->renderizar('editarayudante');
                exit;
            }
            //carga la vista del formulario con los datos cargados
            $this->_view->parametrosVista['datosayudante'] = $datosayudante;
            $this->_view->renderizar('editarayudante');
            exit;
        } else {
            $this->redireccionar();
            exit;
        }
    }

}
