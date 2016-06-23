<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of administradorController
 *
 * @author MatiasBarrera
 */
class adminController extends Controller {

    private $admin;

    public function __construct() {
        parent::__construct();
        $this->admin = $this->loadModel('admin');
    }

    public function index() {

        $this->_view->renderizar('admin');
        exit;
    }

    public function clientes() {
        if (!Session::get('autenticado')) {
            $this->redireccionar();
        }
        if (Session::get('tipoperfil') == "administrador") {
            $dato=$this->admin->prueba();
            
            $listadocliente = $this->admin->getClientes();
            $this->_view->parametrosVista['listadoclientes'] = $listadocliente;
          
             $this->_view->parametrosVista['datos']=$dato;
            $this->_view->renderizar('clientes', 'clientes');
           
            exit;
        } else {
            $this->redireccionar();
            exit;
        }
    }

    public function altacliente() {
        if (!Session::get('autenticado')) {
//            Session::set('error', true);

            $this->redireccionar();
        }
        if (Session::get('tipoperfil') == "administrador") {
            if ($this->getNum('enviar') == 2) {  // hizo el submit del formulario
                if (($this->getTexto('nombre')) and ($this->getTexto('apellido')) and ($this->getTexto('cuit') ) and ($this->getTexto('correo')) and ($this->getTexto('domicilio')) and ($this->getTexto('rsocial')) and ($this->getTexto('condimpositiva')))
                    $ok = $this->admin->insertarCliente($this->getTexto('nombre'), $this->getTexto('apellido'), $this->getTexto("correo"), $this->getTexto("domicilio"), $this->getTexto('rsocial'), $this->getTexto('cuit'), $this->getTexto('condimpositiva'));
                if ($ok) {
                    $this->_view->parametrosVista['mensaje'] = "El cliente ha sido de alta exitosamente";
                     $this->redireccionar('admin/clientes');

                    exit;
                } else {
                    $this->_view->parametrosVista['mensaje'] = "El cuit ingresado ya existe !";
                    $this->_view->renderizar('altacliente','altacliente');
                    exit;
                }
            }
            $this->_view->renderizar('altacliente','altacliente');
            exit;
        } else {
            $this->redireccionar();
            exit;
        }
    }

    public function editarcliente( $id = false) {


        if (!Session::get('autenticado')) {
            $this->redireccionar();
        }
        if (Session::get('tipoperfil') == "administrador") {
            $datoscliente = $this->admin->getDatoCliente($this->filtrarInt($id));
            if ($this->getInt('guardar') == 1) {
                $this->admin->editarCliente($this->getTexto('nombre'), $this->getTexto('apellido'), $this->getTexto("correo"), $this->getTexto("domicilio"), $this->getTexto('rsocial'), $this->getTexto('cuit'), $id,$this->getTexto('condimpositiva'));
                $this->_view->parametrosVista['mensaje'] = "Sus datos han sido modificados";
                $this->redireccionar('admin/clientes');
                exit;
            }

            $this->_view->parametrosVista['datoscliente'] = $datoscliente;
            $this->_view->renderizar('editarcliente', 'editarcliente');
        } else {
            $this->redireccionar();
            exit;
        }
    }

    public function eliminarcliente($id=false) {


        if (!Session::get('autenticado')) {
            //Session::set('error', true);

            $this->redireccionar();
        }
        if (Session::get('tipoperfil') == "administrador") {

            $this->admin->eliminarCliente($this->filtrarInt($id));
            $this->redireccionar('admin/clientes');
            exit;
        } else {
            $this->redireccionar();
            exit;
        }
    }

    public function socios() {
        if (!Session::get('autenticado')) {
            $this->redireccionar();
        }
        if (Session::get('tipoperfil') == "administrador") {
            $listadosocio = $this->admin->getSocios();
            $this->_view->parametrosVista['listadosocios'] = $listadosocio;
            $this->_view->renderizar('socios', 'socios');
            exit;
        } else {
            $this->redireccionar();
            exit;
        }
    }

    public function altasocio() {


        if (!Session::get('autenticado')) {
            $this->redireccionar();
        }
        if (Session::get('tipoperfil') == "administrador") {
            if ($this->getNum('enviar') == 2) {  // hizo el submit del formulario
                if (($this->getTexto('nombre')) || ($this->getTexto('apellido')) || ($this->getTexto('cuit') ) || ($this->getTexto('correo')) || ($this->getTexto('domicilio')) || ($this->getTexto('rsocial')) || ($this->getTexto('usuario') || $this->getTexto('clave') || $this->getTexto('latitud') || $this->getTexto('longitud'))
                ) 
                {
                    
                    $resultadoalta = $this->admin->insertarSocio($this->getTexto('nombre'), $this->getTexto('apellido'), $this->getTexto("correo"), $this->getTexto("domicilio"), $this->getTexto('rsocial'), $this->getTexto('cuit'), $this->getTexto('usuario'), $this->getTexto('clave'),$this->getNum("cantidad"),$this->getTexto("nomAyu1"),$this->getTexto("apeAyu1"),$this->getTexto("dniAyu1"),$this->getTexto("usuarioAyu1"),$this->getTexto("claveAyu1"),$this->getTexto("nomAyu2"),$this->getTexto("apeAyu2"),$this->getTexto("dniAyu2"),$this->getTexto("usuarioAyu2"),$this->getTexto("claveAyu2"), $this->getTexto('latitud'), $this->getTexto('longitud'));
                    if ($resultadoalta == "exito") {
                        $this->_view->parametrosVista['mensaje'] = "El socio ha sido de alta exitosamente";
                         $this->redireccionar('admin/socios');
                        exit;
                    }

                    if ($resultadoalta == "errorusuario") {
                        $this->_view->parametrosVista['mensaje'] = "El usuario ingresado ya existe !";
                        $this->_view->renderizar('altasocio','altasocio');
                        exit;
                    }
                    if ($resultadoalta == "errorcuit") {
                        $this->_view->parametrosVista['mensaje'] = "El cuit ingresado ya existe !";
                        $this->_view->renderizar('altasocio','altasocio');
                        exit;
                    }

                    if ($resultadoalta == "erroruserUno") {
                        $this->_view->parametrosVista['mensaje'] = "El usuario ingresado como ayudante 1 ya existe !";
                        $this->_view->renderizar('altasocio','altasocio');
                        exit;
                    }
                    if ($resultadoalta == "erroruserDos") {
                        $this->_view->parametrosVista['mensaje'] = "El usuario ingresado como ayudante 2 ya existe !";
                        $this->_view->renderizar('altasocio','altasocio');
                        exit;
                    }
                }
            }
            $this->_view->renderizar('altasocio','altasocio');
            exit;
        } else {
            $this->redireccionar();
            exit;
        }
    }

    public function editarsocio($id=false) {
        if (!Session::get('autenticado')) {
            $this->redireccionar();
        }
        if (Session::get('tipoperfil') == "administrador") {
            if ($this->getNum('guardar') == 1) {
                $this->admin->editarSocio($this->getTexto('nombre'), $this->getTexto('apellido'), $this->getTexto("correo"), $this->getTexto("domicilio"), $this->getTexto('rsocial'), $this->getTexto('cuit'), $this->getTexto('usuario'), $this->getTexto('clave'), $this->getNum("comboperfil"), $this->getTexto("latitud"), $this->getTexto("longitud"), $this->getTexto("idAyu1"), $this->getTexto("nomAyu1"),$this->getTexto("apeAyu1"),$this->getTexto("dniAyu1"),$this->getTexto("usuarioAyu1"),$this->getTexto("claveAyu1"),$this->getTexto("idAyu2"),$this->getTexto("nomAyu2"),$this->getTexto("apeAyu2"),$this->getTexto("dniAyu2"),$this->getTexto("usuarioAyu2"),$this->getTexto("claveAyu2"), $id);
                $this->_view->parametrosVista['mensaje'] = "Sus datos han sido modificados";
                $this->redireccionar('admin/socios');
                
            }
            
            $datosayudantes = $this->admin->getDatosAyudantesSocio($this->filtrarInt($id));
            $datossocio = $this->admin->getDatoSocio($this->filtrarInt($id));
            
            $perfiles = $this->admin->getPerfiles();
            //carga la vista del formulario con los datos cargados
            $this->_view->parametrosVista['listadoayudantes'] = $datosayudantes;
            $this->_view->parametrosVista['listadoperfiles'] = $perfiles;
            $this->_view->parametrosVista['datossocio'] = $datossocio;
            $this->_view->renderizar('editarsocio','editarsocio');
            exit;
        } else {
            $this->redireccionar();
            exit;
        }
    }

    public function eliminarsocio($id=false) {
        if (!Session::get('autenticado')) {
            $this->redireccionar();
        }
        if (Session::get('tipoperfil') == "administrador") {
            $this->admin->eliminarSocio($this->filtrarInt($id));
            $this->redireccionar("admin/socios");
            exit;
        } else {
            $this->redireccionar();
            exit;
        }
    }

    public function inquilinos() {
        if (!Session::get('autenticado')) {
            $this->redireccionar();
        }
        if (Session::get('tipoperfil') == "administrador") {
            $listadoinquilino = $this->admin->getInquilinos();
            $this->_view->parametrosVista['listadoinquilinos'] = $listadoinquilino;
            $this->_view->renderizar('inquilinos', 'inquilinos');
            exit;
        } else {
            $this->redireccionar();
            exit;
        }
    }

    public function altainquilino() {
        if (!Session::get('autenticado')) {
            $this->redireccionar();
        }
        if (Session::get('tipoperfil') == "administrador") {
            if ($this->getNum('enviar') == 2) {  // hizo el submit del formulario
                if (($this->getTexto('nombre')) and ($this->getTexto('apellido')) and ($this->getTexto('cuit')) and ($this->getTexto('correo')) and ($this->getTexto('domicilio')) and ($this->getTexto('rsocial')) and ($this->getTexto('usuario')) and ($this->getTexto('clave')) and ($this->getTexto('latitud')) and ($this->getTexto('longitud'))) {
                 
                    $resultadoalta = $this->admin->insertarInquilino($this->getTexto('nombre'), $this->getTexto('apellido'), $this->getTexto("correo"), $this->getTexto("domicilio"), $this->getTexto('rsocial'), $this->getTexto('cuit'), $this->getTexto('usuario'), $this->getTexto('clave'),$this->getNum("cantidad"),$this->getTexto("nomAyu1"),$this->getTexto("apeAyu1"),$this->getTexto("dniAyu1"),$this->getTexto("usuarioAyu1"),$this->getTexto("claveAyu1"),$this->getTexto("nomAyu2"),$this->getTexto("apeAyu2"),$this->getTexto("dniAyu2"),$this->getTexto("usuarioAyu2"),$this->getTexto("claveAyu2"), $this->getTexto('latitud'), $this->getTexto('longitud'));
      
                    if ($resultadoalta == "exito") {
                        $this->_view->parametrosVista['mensaje'] = "El inquilino ha sido de alta exitosamente";
                          $this->redireccionar('admin/inquilinos');
                        exit;
                    }
                    if ($resultadoalta == "errorusuario") {
                        $this->_view->parametrosVista['mensaje'] = "El usuario ingresado ya existe !";
                        $this->_view->renderizar('altainquilino','altainquilino');
                        exit;
                    }
                    if ($resultadoalta == "errorcuit") {
                        $this->_view->parametrosVista['mensaje'] = "El cuit ingresado ya existe !";
                        $this->_view->renderizar('altainquilino','altainquilino');
                        exit;
                    }

                    if ($resultadoalta == "erroruserUno") {
                        $this->_view->parametrosVista['mensaje'] = "El usuario ingresado como ayudante 1 ya existe !";
                        $this->_view->renderizar('altainquilino','altainquilino');
                        exit;
                    }
                    if ($resultadoalta == "erroruserDos") {
                        $this->_view->parametrosVista['mensaje'] = "El usuario ingresado como ayudante 2 ya existe !";
                        $this->_view->renderizar('altainquilino','altainquilino');
                        exit;
                    }
                }
            }
            $this->_view->renderizar('altainquilino','altainquilino');
            exit;
        } else {
            $this->redireccionar();
            exit;
        }
    }

    public function editarinquilino($id=false) {

        if (!Session::get('autenticado')) {
            $this->redireccionar();
        }
        if (Session::get('tipoperfil') == "administrador") {
            
            if ($this->getNum('guardar') == 1) {
                $this->admin->editarInquilino($this->getTexto('nombre'), $this->getTexto('apellido'), $this->getTexto("correo"), $this->getTexto("domicilio"), $this->getTexto('rsocial'), $this->getTexto('cuit'), $this->getTexto('usuario'), $this->getTexto('clave'), $this->getNum("comboperfil"), $this->getTexto("latitud"), $this->getTexto("longitud"), $this->getTexto("idAyu1"), $this->getTexto("nomAyu1"),$this->getTexto("apeAyu1"),$this->getTexto("dniAyu1"),$this->getTexto("usuarioAyu1"),$this->getTexto("claveAyu1"),$this->getTexto("idAyu2"),$this->getTexto("nomAyu2"),$this->getTexto("apeAyu2"),$this->getTexto("dniAyu2"),$this->getTexto("usuarioAyu2"),$this->getTexto("claveAyu2"), $id);
                $this->_view->parametrosVista['mensaje'] = "Sus datos han sido modificados";
                $this->redireccionar('admin/inquilinos');
               
            }
            $perfiles = $this->admin->getPerfiles();
            $datosayudantes = $this->admin->getDatosAyudantesInquilino($this->filtrarInt($id));
            $datosinquilino = $this->admin->getDatoInquilino($this->filtrarInt($id));
          
            //carga la vista del formulario con los datos cargados
            $this->_view->parametrosVista['listadoayudantes'] = $datosayudantes;
            $this->_view->parametrosVista['listadoperfiles'] = $perfiles;
            $this->_view->parametrosVista['datosinquilino'] = $datosinquilino;
            $this->_view->renderizar('editarinquilino','editarinquilino');
            exit;
        } else {
            $this->redireccionar();
            exit;
        }
    }

    public function eliminarinquilino($id=false) {
        if (!Session::get('autenticado')) {
            $this->redireccionar();
        }
        if (Session::get('tipoperfil') == "administrador") {
            $this->admin->eliminarInquilino($this->filtrarInt($id));
            $this->redireccionar("admin/inquilinos");
            exit;
        } else {
            $this->redireccionar();
            exit;
        }
    }

    public function especies() {
        if (!Session::get('autenticado')) {
            $this->redireccionar();
        }
        if (Session::get('tipoperfil') == "administrador") {
            $listadoespecies = $this->admin->getEspecies();
            $this->_view->parametrosVista['listadoespecies'] = $listadoespecies;
            $this->_view->renderizar('especies', 'especies');
            exit;
        } else {
            $this->redireccionar();
            exit;
        }
    }

    public function altaespecie() {
        if (!Session::get('autenticado')) {
            $this->redireccionar();
        }
        if (Session::get('tipoperfil') == "administrador") {
            if ($this->getNum('enviar') == 2) { 
                if (!($this->getTexto('input_checked_color'))  and !($this->getTexto('input_checked_especie'))) { //sino seleccionaste el check y seleccionaste los combos de especie y color
                                       
                    $this->admin->insertarEspecieSeleccionandoCombo($this->getTexto('planta'), $this->getTexto('nombrecientifico'), $this->getTexto('habitat'), $this->getTexto('region'), $this->getTexto('epoca'), $this->getNum('seleccion_color'), $this->getNum('seleccion_especie'), $this->getNum('precio'));
                    $this->_view->parametrosVista['mensaje'] = "La planta ha sido de alta exitosamente";   
                      $this->redireccionar('admin/especies');
                    exit;
                 }
                if ($this->getNum('input_checked_color') and $this->getNum('input_checked_especie')) {
                    
                     $existeColor=$this->admin->validarIngresoColor($this->getTexto('input_color'));
                     if($existeColor['cantidad']==0){
                     $this->admin->insertarEspecieIngresandoInput($this->getTexto('planta'), $this->getTexto('nombrecientifico'), $this->getTexto('habitat'), $this->getTexto('region'), $this->getTexto('epoca'), $this->getTexto('input_color'), $this->getTexto('input_especie'), $this->getNum('precio'));
                     $this->_view->parametrosVista['mensaje'] = "La planta ha sido de alta exitosamente";   
    
                    $this->redireccionar('admin/especies');
                    exit;
                     }
                     else{
                        
                         $this->_view->parametrosVista['mensaje'] = "El color ingresado ya existe, ingrese otro"; 
                         $this->_view->renderizar('altaespecie','altaespecie');
                         exit;
                         
                     }
                }
                if (($this->getNum('input_checked_color'))  and !($this->getNum('input_checked_especie'))) { //sino seleccionaste el check y seleccionaste los combos de especie y color
                    $this-> admin->insertarEspecieIngresandoInputColorConSeleccionComboEspecie($this->getTexto('planta'),$this->getTexto('nombrecientifico'),$this->getTexto('habitat'),$this->getTexto('region'),$this->getTexto('epoca'),$this->getTexto('input_color'),$this->getNum('seleccion_especie'),$this->getNum('precio')); 
                    $this->_view->parametrosVista['mensaje'] = "La planta ha sido de alta exitosamente";   
                   $this->redireccionar('admin/especies');                
                     exit;
                    
                }
                if ($this->getNum('input_checked_especie')  and !($this->getNum('input_checked_color'))){

                    $this->admin->insertarEspecieIngresandoInputEspecieConSeleccionComboColor($this->getTexto('planta'),$this->getTexto('nombrecientifico'),$this->getTexto('habitat'),$this->getTexto('region'),$this->getTexto('epoca'),$this->getTexto('input_especie'),$this->getNum('seleccion_color'),$this->getNum('precio'));
                    $this->_view->parametrosVista['mensaje'] = "La planta ha sido de alta exitosamente";   
                 $this->redireccionar('admin/especies');   
                    exit;
                }
     }
             
            $colores = $this->admin->getColores();
            $especies = $this->admin->especiesCargadas();
            $this->_view->parametrosVista['colores'] = $colores;
            $this->_view->parametrosVista['especies'] = $especies;
            $this->_view->renderizar('altaespecie','altaespecie');
            exit;
        } 
        else {
            $this->redireccionar();
            exit;
        }
    }

   public function editarespecie($idplanta=false){
     if (!Session::get('autenticado')) {
            $this->redireccionar();
        }
        if (Session::get('tipoperfil') == "administrador") {
            
            if ($this->getNum('guardar') == 1) {
               $this->admin->editarEspecie($this->getTexto('nombre'), $this->getTexto('nombrec'), $this->getTexto("habitat"), $this->getNum("comboespecies"), $this->getTexto('region'), $this->getTexto('epoca'), $this->getNum('combocolores'),$idplanta);
                $this->_view->parametrosVista['mensaje'] = "Los datos han sido modificados";
                $this->redireccionar('admin/especies');
               
            }
           
            $datosespecie =$this->admin->getDatoEspecie($this->filtrarInt($idplanta));
            $especies=$this->admin->especiesCargadas();
            $colores = $this->admin->getColores();

            //carga la vista del formulario con los datos cargados
            $this->_view->parametrosVista['colores']=$colores;
            $this->_view->parametrosVista['especies'] = $especies;
            $this->_view->parametrosVista['datosespecie'] = $datosespecie;
            $this->_view->renderizar('editarespecie','editarespecie');
            exit;
        } 
        else {
            $this->redireccionar();
            exit;
        }
    } 
    public function eliminarespecie($idplanta=false){
    if (!Session::get('autenticado')) {
            $this->redireccionar();
    }
    if (Session::get('tipoperfil') == "administrador") {
            $this->admin->eliminarEspecie($this->filtrarInt($idplanta));
            $this->redireccionar("admin/especies");
            exit;
    } else {
           $this->redireccionar();
            exit;
        }   
        
        
        
    }
       
       
       
       
       
       
   }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

//end de la clase



    
    
    

