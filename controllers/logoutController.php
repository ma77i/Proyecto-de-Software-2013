<?php


class logoutController  extends Controller{
    //put your code here
    
    public function __construct() {
        parent::__construct();
      
    }

    public function index() {
        Session::destroy();
        $this->redireccionar();
        exit;
    }
}
