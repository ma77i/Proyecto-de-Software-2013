<?php

class indexController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->_index = $this->loadModel("index");
    }

    public function index() {
        $datos = $this->_index->listarFlores();
        $this->_view->parametrosVista['listadoFlores'] = $datos;
        $this->_view->renderizar('index', 'inicio');
        exit;
    }

}
?>
