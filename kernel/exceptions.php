<?php

class ExceptionHttpResponse extends Exception {
    
    protected $statusCode;
    
    function __construct($statusCode, $message) {
        $this->statusCode = $statusCode;
        parent::__construct($message);
    }
    
    function getStatusCode() {
        return $this->statusCode;
    }
    
}

class ExceptionPermissionDenied extends ExceptionHttpResponse {
    
    function __construct($message = "Permission Denied") {
        parent::__construct(403, $message);
    }
    
}

class ExceptionNotFound extends ExceptionHttpResponse {
    
    function __construct($message = "Not Found") {
        parent::__construct(404, $message);
    }
    
}

