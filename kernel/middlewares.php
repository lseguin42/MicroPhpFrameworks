<?php

foreach (glob('middlewares/*.php') as $middleware) {
    require_once $middleware;
}