<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of inquilinoController
 *
 * @author MatiasBarrera
 */
class inquilinoController extends Controller {

    private $inquilino;

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->inquilino = $this->loadModel('inquilino');
        $this->ingreso = $this->loadModel('ingreso');
    }

    public function index() {
        $this->_view->parametrosVista ['ingresosInquilino'] = $this->ingreso->historialIngresos(Session::get('idusuario'));
        $this->_view->parametrosVista ['TotalIngresosInquilino'] = $this->ingreso->historialTotalIngresos(Session::get('idusuario'), Session::get('tipoperfil'));
        $this->_view->parametrosVista ['TotalIngresosAyudantes'] = $this->ingreso->historialTotalIngresosAyudantesInquilino(Session::get('idusuario'), Session::get('tipoperfil'));
        $this->_view->renderizar('inquilino','inquilino');
    }

    public function editarinquilino() {

        if (!Session::get('autenticado')) {
            $this->redireccionar();
        }
        if (Session::get('tipoperfil') == "inquilino") {


            $datosinquilino = $this->inquilino->getDatoInquilino(Session::get('idusuario'));
            if ($this->getNum('guardar') == 1) {

                //$this->_view->datos =$_POST;

                $this->inquilino->editarInquilino($this->getTexto('nombre'), $this->getTexto('apellido'), $this->getTexto("correo"), $this->getTexto("domicilio"), $this->getTexto('rsocial'), $this->getTexto('cuit'), Session::get('idusuario'));
                $this->_view->parametrosVista['mensaje'] = "Sus datos han sido modificados";
                $this->redireccionar('inquilino');
                exit;
            }
            //carga la vista del formulario con los datos cargados
            $this->_view->parametrosVista['datosinquilino'] = $datosinquilino;
            $this->_view->renderizar('editarinquilino');
            exit;
        } else {
            $this->redireccionar();
        }
    }

}
