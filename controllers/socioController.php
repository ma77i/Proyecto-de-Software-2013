<?php

class socioController extends Controller {

    private $socio;

    public function __construct() {
        parent::__construct();
        $this->socio = $this->loadModel('socio');
        $this->ingreso = $this->loadModel('ingreso');
    }

    public function index() {
        $this->_view->parametrosVista ['ingresosSocio'] = $this->ingreso->historialIngresos(Session::get('idusuario'));
        $this->_view->parametrosVista ['TotalIngresosSocio'] = $this->ingreso->historialTotalIngresos(Session::get('idusuario'), Session::get('tipoperfil'));
        $this->_view->parametrosVista ['TotalIngresosAyudantes'] = $this->ingreso->historialTotalIngresosAyudantesSocio(Session::get('idusuario'), Session::get('tipoperfil'));
        $this->_view->renderizar('socio','socio');
    }
    
     

    public function editarsocio() {

        if (!Session::get('autenticado')) {
            $this->redireccionar();
        }
        if (Session::get('tipoperfil') == "socio") {


            $datossocio = $this->socio->getDatoSocio(Session::get('idusuario'));
            if ($this->getNum('guardar') == 1) {

                //$this->_view->datos =$_POST;

                $this->socio->editarSocio($this->getTexto('nombre'), $this->getTexto('apellido'), $this->getTexto("correo"), $this->getTexto("domicilio"), $this->getTexto('rsocial'), $this->getTexto('cuit'), Session::get('idusuario'));
                $this->_view->parametrosVista['mensaje'] = "Sus datos han sido modificados";
                $this->redireccionar('socio');
                exit;
            }
            //carga la vista del formulario con los datos cargados
            $this->_view->parametrosVista['datossocio'] = $datossocio;
            $this->_view->renderizar('editarsocio');
            exit;
        } else {
            $this->redireccionar();
            exit;
        }
    }

}
       
