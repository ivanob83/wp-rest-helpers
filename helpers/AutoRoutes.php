<?php

add_action( 'rest_api_init', 'registerDefaultRoutes');

function registerDefaultRoutes() {
    $autoRoutes = AutoRoutes;
    foreach($autoRoutes AS $className) {
        if(isset($className::$autoRoutes)) {
            $controller = $className.'Controller';
            $classUrl = $className::$name;
            
            register_rest_route(
                'v2', $classUrl,
                array(
                    'methods'  => 'GET',
                    'callback' => [$controller, 'index'],
                )
            );

            register_rest_route(
                'v2', $classUrl.'/(?P<id>[\d]+)',
                array(
                  'methods'  => 'GET',
                  'callback' => [$controller, 'show'],
                )
              );
              register_rest_route(
                'v2', $classUrl,
                array(
                  'methods'  => 'POST',
                  'callback' => [$controller, 'insert'],
                  'permission_callback' => function () {
                    return true;
                    return is_user_logged_in();
                  }
                )
              );
              register_rest_route(
                'v2', $classUrl,
                array(
                  'methods'  => 'PUT',
                  'callback' => [$controller, 'update'],
                  'permission_callback' => function () {
                    return is_user_logged_in();
                  }
                )
              );
              register_rest_route(
                'v2', $classUrl,
                array(
                  'methods'  => 'DELETE',
                  'callback' => [$controller, 'delete'],
                  'permission_callback' => function () {
                    return is_user_logged_in();
                  }
                )
              );
        }
    }
    
}