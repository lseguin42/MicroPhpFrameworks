<?php
// Middleware pour la gestion d'authentification

function isAuthenticate(Controller $controller) {
    
    $pdoService = $controller->getService('PDO');
    $conn = $pdoService->getConnection();
    
    $controller->set('user', array(
        'name' => 'lseguin',
        'email' => 'lseguin@student.42.fr',
        'type' => 'admin'
    ));
    
}

function isRestrictedFor(Controller $controller, $userType) {
    isAuthenticate($controller);
    if ($controller->get('user')['type'] !== $userType)
        throw new ExceptionPermissionDenied();
}