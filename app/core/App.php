<?php


// Routing
class App{

    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];


    public function __construct(){
        $url = $this->parseURL();
       
        // controller
        if (file_exists('../app/controller/' . $url[0] . '.php')){ // ada ga file home.php di controller
            $this->controller = $url[0]; // controller baru
            unset($url[0]);
        }

        require_once '../app/controller/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // method
        if (isset($url[1])){
            if (method_exists($this->controller, $url[1])){
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // parameter
        if(!empty($url)){
            $this->params = array_values($url);
        }

        // jalankan controller dan method, serta kirimkan params jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);
    
    }

    public function parseURL(){
        if(isset($_GET['url'])){
            $url = rtrim($_GET['url'], '/'); // rtrim (penghilang slash di url)
            $url = filter_var($url, FILTER_SANITIZE_URL); // url bersih dari karakter aneh
            $url = explode('/', $url); // pemecah string dari url
            return $url;
        }
    }
}