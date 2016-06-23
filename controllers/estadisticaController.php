<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of estadisticaController
 *
 * @author MatiasBarrera
 */
class estadisticaController extends Controller {
    //put your code here
    private $estadistica;
    
    public function __construct() {
        parent::__construct();
       $this->estadistica = $this->loadModel('estadistica');
    }

    public function index() {
      if (!Session::get('autenticado')) {   
         
           $this->redireccionar();
     }
     if (Session::get('tipoperfil') == "administrador") {
         $listadosocios=$this->estadistica->getSocios();
          $this->_view->parametrosVista['listadosocios'] = $listadosocios;
         $this->_view->renderizar('estadistica');
        }
        
      else {
            $this->redireccionar(Session::get('tipoperfil'));
        }
    }
    
    public function getDataSinFiltro(){
        
        print json_encode($this->estadistica->getVentas());
        
    }
    public function getData($fechai,$fechaf){
        
        print json_encode($this->estadistica->getVentasGenerales($fechai,$fechaf));
        
    }
    
     public function getDataPorMes($mes){
        
        print json_encode($this->estadistica->getEspeciesPorMes($mes));
        
    }
    
    public function getDataEspecieMasyMenosVendida($m,$a){
       
       print json_encode($this->estadistica->getEspecieMasyMenosVendida($m,$a,$m,$a)); 
        
        
        
    }
    public function getDataComprasClientes(){
        
        print json_encode($this->estadistica->getComprasClientes());
    }
    
    
    
    
 public function getMarkers(){
        
        print json_encode($this->estadistica->getMarkers());
        
    }
    
    public function getMarkersMenor(){
        
        print json_encode($this->estadistica->getMarkersMenor());
        
    }
    
   public function getVentasPorSocio($idsocio){
       
       print json_encode($this->estadistica->ventasPorSocio($idsocio));
       
       
       
   }
   
   public function getVentasPorSocioEntreFechas($idsocio,$f1, $f2){
      
       print json_encode($this->estadistica->ventasPorSocioEntreFechas($idsocio,$f1,$f2));
   }
    
    
    
    
    
    
    
}
