<?php

class loginController extends Controller {

    private $_login;

    public function __construct() {
        parent::__construct();
        $this->_login = $this->loadModel('login');
    }

    public function index() {


        if (Session::get('autenticado')) {
//            Session::set('error', true);
            $this->redireccionar(); // redireccion por que esta logeado
        }
//       
//        $this->_view->titulo='Iniciar Sesion';

        if ($this->getNum('enviar') == 2) {

////            $this->_view->datos=$_POST;
////            
            if (!($this->getTexto('Usuario'))) {

                $this->_view->parametrosVista['mensaje'] = 'Ingrese un usuario';
                $this->_view->renderizar('login', 'Iniciar Sesion');

                exit;
            }
            if (!($this->getTexto('Password'))) {
                $this->_view->parametrosVista['mensaje'] = 'Ingrese un password';
                $this->_view->renderizar('login', 'Iniciar Sesion');
                exit;
            }

            $datos = $this->_login->getUser($this->getTexto('Usuario'), $this->getTexto('Password'));
           $esActivo= $this->_login->esActivo($datos['idusuario'], $datos['perfil']);
           
            if (!$datos) {  // si no devuelve ninguna tupla
                $this->_view->parametrosVista['mensaje'] = 'Usuario y/o password inválidos';
                $this->_view->renderizar('login', 'Iniciar Sesion');
                exit;
            }
            if(!$esActivo){
                $this->_view->parametrosVista['mensaje'] = 'Usuario inactivo.';
                $this->_view->renderizar('login', 'Iniciar Sesion');
                exit;
            }
            if ($datos) {
                Session::set('autenticado', true);
                Session::set('idusuario', $datos['idusuario']);
                Session::set('usuario', $datos['usuario']);
                Session::set('tipoperfil', $datos['perfil']);
               
            }
            if (Session::get('tipoperfil') == "administrador") {

                $this->redireccionar("admin");
            } else if (Session::get('tipoperfil') == "socio") {
                $this->redireccionar("socio");
            } else if (Session::get('tipoperfil') == "inquilino") {
                $this->redireccionar("inquilino");
            } else {
                $this->redireccionar("ayudante");
            }
        }

        $this->_view->renderizar('login', 'login');
        exit;
    }

}

?>
