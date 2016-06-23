<?php

class ingresoController extends Controller {

    private $_ingreso;

    public function __construct() {
        parent::__construct();
        $this->_ingreso = $this->loadModel("ingreso");
    }

    public function index() {

        if (!Session::get('autenticado') || (!Session::get('tipoperfil') == "socio")) {
            $this->redireccionar();
        }

        if ($this->getNum('enviar') == 2) {
            $this->ingresarPlanta();
            $this->redireccionar(Session::get('tipoperfil'));
        }


        $this->_view->parametrosVista['listadoespecies'] = $this->listarEspecies();
        $this->_view->parametrosVista['listadocolores'] = $this->listarColores();
        $this->_view->renderizar('ingreso', 'ingreso');
        exit;
    }

    public function listarEspecies() {
        return ($this->_ingreso->listarEspecies());
    }

    public function listarPlantasDeEspecie() {

        if ($this->getNum('especie')) {
            print json_encode($this->_ingreso->listarPlantasPorEspecie($this->getNum('especie')));
        }
        exit;
    }

    public function listarColores() {
        return ($this->_ingreso->listarColoresPlantas());
    }

    public function ingresarPlanta() {

        if ($this->getNum("combocanting")==1)
            $this->_ingreso->ingresar1Planta($this->getNum("comboespecies"), $this->getNum("comboplantas"), $this->getNum("combocolores"), $this->getNum("cantidadingresada"), Session::get('idusuario'));
        else if ($this->getNum("combocanting")==2) 
            $this->_ingreso->ingresar2Plantas($this->getNum("comboespecies"), $this->getNum("comboplantas"), $this->getNum("combocolores"), $this->getNum("cantidadingresada"), 
                    $this->getNum("comboespecies2"), $this->getNum("comboplantas2"), $this->getNum("combocolores2"), $this->getNum("cantidadingresada2"),
                    Session::get('idusuario'));
    
        else
            $this->_ingreso->ingresar3Plantas($this->getNum("comboespecies"), $this->getNum("comboplantas"), $this->getNum("combocolores"), $this->getNum("cantidadingresada"),
                    $this->getNum("comboespecies2"), $this->getNum("comboplantas2"), $this->getNum("combocolores2"), $this->getNum("cantidadingresada2"),
                    $this->getNum("comboespecies3"), $this->getNum("comboplantas3"), $this->getNum("combocolores3"), $this->getNum("cantidadingresada3"),Session::get('idusuario'));
    }

    public function editarIngreso($idingreso) {

        if (!Session::get('autenticado')) {
            $this->redireccionar();
        }

        $datosingreso = $this->_ingreso->getDatoIngreso($idingreso);
        if ($this->getNum('guardar') == 1) {
            $this->_ingreso->editarIngreso($this->getTexto('cantidad'), $idingreso);
            $this->_view->parametrosVista['mensaje'] = "Sus datos han sido modificados";
            $this->redireccionar(Session::get('tipoperfil'));
            exit;
        }
        //carga la vista del formulario con los datos cargados
        $this->_view->parametrosVista['datosingreso'] = $datosingreso;
        $this->_view->renderizar('editaringreso');
        exit;
    }

    public function eliminarIngreso($idingreso) {
        if (!Session::get('autenticado')) {
            $this->redireccionar();
        }

        $this->_ingreso->eliminarIngreso($this->filtrarInt($idingreso));
        $this->redireccionar(Session::get('tipoperfil'));

        exit;
    }

}
