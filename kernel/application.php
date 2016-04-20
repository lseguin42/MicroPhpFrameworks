<?php
require_once 'kernel/exceptions.php';
require_once 'kernel/controllers.php';
require_once 'kernel/middlewares.php';
require_once 'kernel/services.php';
require_once 'kernel/api.php';

define('MFPHP_NEXT', -1);
define('MFPHP_TERMINATE', 1);
define('MFPHP_ABORT', 2);

class Application {
    
    private $conf = [];
    private $uri;
    private $method;
    private $controllerManagers = [];
    private $services = [];
    
    function Application($method, $uri) {
        $this->method = $method;
        $this->uri = $uri;
    }
    
    function getURI() {
        return $this->uri;
    }
    
    function getMethod() {
        return $this->method;
    }
    
    function getController() {
        return $this->controller;
    }
    
    function getService($name) {
        if ($services[$name])
            return $services[$name];
        $ClassService = $name . 'Service';
        if (!is_subclass_of($ClassService, 'Service')) {
            return null;
        }
        $config = $this->getConfig($name);
        if (!$config)
            $config = [];
        return $services[$name] = new $ClassService($config);
    }
    
    function configure($name, $config) {
        $this->conf[$name] = $config;
    }
    
    function getConfig($name) {
        return $this->conf[$name];
    }
    
    private function buildControllerManagers() {
        foreach (routeApi() as $api) {
            $routeMatcher = convertPattern($api['route']);
            if ($api['method'] === $this->method
                && preg_match($routeMatcher, $this->uri, $params)) {
                foreach ($params as $key => $value) {
                    if (is_int($key))
                        unset($params[$key]);
                }
                $controller = new $api['className']($this);
                $ctrlManager = new ControllerManager(
                    $controller,
                    $api['methodName'],
                    $params
                );
                $this->controllerManagers[] = $ctrlManager;
            }
        }
    }
    
    function run() {
        $this->buildControllerManagers();
        if (count($this->controllerManagers) === 0)
            throw new ExceptionNotFound();
        foreach($this->controllerManagers as $manager) {
            if ($manager->perform() !== MFPHP_NEXT)
                return ;
        }
    }
    
}