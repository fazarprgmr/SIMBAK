<?php

if (!function_exists('isActive')) {
    function isActive($routes, $output = 'active')
    {
        if (!is_array($routes)) {
            $routes = [$routes];
        }

        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return $output;
            }
        }

        return '';
    }
}
