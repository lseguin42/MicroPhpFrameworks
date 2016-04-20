<?php

class ControllerManager {
    
    private $className;
    private $method;
    private $params;
    
    private $controller;
    
    function ControllerManager($controller, $method, $params) {
        $this->className = get_class($controller);
        $this->method = $method;
        $this->params = $params;
        $this->controller = $controller;
    }
    
    function triggerMiddleware() {
        $r = new ReflectionMethod($this->className, $this->method);
        $doc = $r->getDocComment();
        $match = preg_match_all('/@middleware\s+(.*)\n/', $doc, $middlewares, PREG_PATTERN_ORDER);
        if (!$match)
            return ;
        foreach($middlewares[1] as $middleware) {
            $middleware = explode(':', $middleware);
            $middlewareFunction = $middleware[0];
            $middleware[0] = $this->controller;
            call_user_func_array($middlewareFunction, $middleware);
        }
    }
    
    function perform() {
        $this->triggerMiddleware();
        return call_user_func_array(array($this->controller, $this->method), $this->params);
    }

}

class Controller {
    
    protected $datas = Array();
    private $application;
    
    function Controller(Application $application) {
        $this->application = $application;
    }
    
    function getService($name) {
        return $this->application->getService($name);
    }
    
    function get($name) {
        return $this->datas[$name];
    }
    
    function set($name, $data) {
        $this->datas[$name] = $data;
    }
    
}

foreach (glob('controllers/*Controller.php') as $controller) {
    require_once $controller;
}