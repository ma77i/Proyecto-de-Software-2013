<?php

class View {

    private $_controlador;
    private $_js;

    public function __construct(Request $peticion) {
        $this->_controlador = $peticion->getControlador();
        $this->_js = array();
    }

    public function renderizar($vista, $item = false) {

        $menu = array(
            array(
                'id' => 'inicio',
                'titulo' => 'Inicio',
                'enlace' => BASE_URL
            ),
            array(
                'id' => 'post',
                'titulo' => 'Post',
                'enlace' => BASE_URL . 'post'
            )
        );

        $js = array();

        if (count($this->_js)) {
            $js = $this->_js;
        }


        $rutaView = $this->_controlador . DS . $vista . '.twig';

        if (!is_readable(ROOT . 'views' . DS . $rutaView)) {
            throw new Exception('No se pudo cargar la vista correspondiente!');
            exit;
        }

        if (isset($this->parametrosVista)) { //si esta definida la variable
            $parametrosVista = $this->parametrosVista;
        } else {
            $parametrosVista = null;
        }
        Twig_Autoloader::register();
        $dir = ROOT . 'views' . DS;
        $loader = new Twig_Loader_Filesystem($dir);
        $twig = new Twig_Environment($loader);

        $template = $twig->loadTemplate('layout' . DS . DEFAULT_LAYOUT . DS . "template.twig");
        $template->display(array(
            'ruta_css' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/css/',
            'ruta_img' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/img/',
            'ruta_js' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/js/',
            'header' => 'layout' . DS . DEFAULT_LAYOUT . DS . 'header.twig',
            'footer' => 'layout' . DS . DEFAULT_LAYOUT . DS . 'footer.twig',
            'menu' => 'layout' . DS . DEFAULT_LAYOUT . DS . 'menu.twig',
            'tipoperfil' => Session::get("tipoperfil"),
            'logueado' => Session::get("autenticado"),
            'js' => $js,
            'siteName' => SITE_NAME,
            'estilo' => 'estilos.css',
            'home' => BASE_URL,
            'js' => $this->_js,
            'rutaVista' => $rutaView,
            'company' => COMPANY,
            'parametros' => $parametrosVista,
            'seccionActual' => $item, // sección actual de la pagina
        ));
    }

    public function setJs(array $js) {
        if (is_array($js) && count($js)) {
            for ($i = 0; $i < count($js); $i++) {
                $this->_js[] = BASE_URL . 'views/' . $this->_controlador . '/js/' . $js[$i] . '.js';
            }
        } else {
            throw new Exception('Error de js');
        }
    }

    private function menuDefault() {

        $menu = array(
            array(
                'id' => 'inicio',
                'titulo' => 'Inicio',
                'enlace' => HOME
            )
        );

        if (Session::get('autenticado')) {
            if (Session::get('administrador')) {
                $menu[] = array(
                    'id' => 'Administracion',
                    'titulo' => 'Administracion',
                    'enlace' => HOME . 'admin'
                );
            }
        }

        return $menu;
    }

}

?>
