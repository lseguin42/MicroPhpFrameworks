<?php

function routeApi() {
    static $_API_ROUTER = null;

    if ($_API_ROUTER !== null)
        return $_API_ROUTER;
    $_API_ROUTER = [];
    foreach (get_declared_classes() as $className) {
        if (is_subclass_of($className, 'Controller')) {
            $methods = get_class_methods($className);
            foreach ($methods as $method) {
                $r = new ReflectionClass($className);
                $doc = $r->getDocComment();
                $match = preg_match('/@apiPrefix\s+(\/.*)\n/', $doc, $api);
                $prefix = ($match ? $api[1] : '');
                $r = new ReflectionMethod($className, $method);
                $doc = $r->getDocComment();
                $match = preg_match('/@api\s+(POST|GET|PUT|DELETE)?\s*(\/.*)\n/', $doc, $api);
                if (!$match)
                    continue ;
                $methodHTTP = ($api[1] == '' ? 'GET' : $api[1]);
                $route = $prefix . $api[2];
                $_API_ROUTER[] = Array(
                    'method' => $methodHTTP,
                    'route' => $route,
                    'className' => $className,
                    'methodName' => $method
                );
            }
        }
    }
    return $_API_ROUTER;
}

function convertPattern($regex) {
    $out = preg_replace('/:([a-z]+)\(/', '(?<$1>', $regex);
    $out = preg_replace('/:([a-z]+)/', '(?<$1>[^/]+)', $out);
    return '#^' . $out . '$#';
}