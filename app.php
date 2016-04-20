<?php
require_once 'kernel/application.php';

$method = 'GET';
if (preg_match('#^/#', $argv[1])) {
    $uri = $argv[1];
} else if (in_array($argv[1], ['POST','GET','PUT','DELETE','HEAD'])
            && preg_match('#^/#', $argv[2])) {
    $method = $argv[1];
    $uri = $argv[2];
} else {
    echo "ERROR PARAM\n";
    exit(-1);
}

$app = new Application($method, $uri);

$app->configure('PDO', Array(
    'driver'    => 'mysql',
    'hostname'  => 'localhost',
    'dbname'    => 'toto',
    'username'  => 'lionel',
    'password'  => 'miaou'
));

try {
    $app->run();
} catch (ExceptionHttpResponse $e) {
    echo $e;
} catch (Exception $e) {
    echo "Internal Error";
}