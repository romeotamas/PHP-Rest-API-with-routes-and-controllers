<?php

    class Route {

        public static function isRouteValid() {

            global $route;
            $uri = $_SERVER['REQUEST_URI'];

            if ($route !== $uri) {
                return 0;
            } else {
                return 1;
            }
        }

    
        private static function registerRoute($routeName, $controllerName) {

            global $route, $controller;
            $route = BASEDIR.$routeName;
            $controller = $controllerName;
        }


        public static function dyn($dyn_routes) {
            
            $route_components = explode('/', $dyn_routes);
            $uri_components = explode('/', substr($_SERVER['REQUEST_URI'], strlen(BASEDIR)-1));

            for ($i = 0; $i < count($route_components); $i++) {
                
                if ($i+1 <= count($uri_components)-1) {
                    $route_components[$i] = str_replace("<$i>", $uri_components[$i+1], $route_components[$i]);
                }
            }
            
            $routeStr = implode($route_components, '/');
            
            return $routeStr;
        }


        public static function set($routeName, $controllerName) {
            
            if (
                $_SERVER['REQUEST_URI'] === BASEDIR.$routeName ||
                $_SERVER['REQUEST_URI'] === BASEDIR.$routeName . "/"
            ) {
                self::registerRoute($routeName, $controllerName);
            } else if (
                    stripos($_GET['url'], "/") !== false &&
                    stripos($routeName, "/") !== false &&
                    strlen(explode("/", $_GET['url'])[1]) > 0 &&
                    explode("/", $_GET['url'])[0] === explode('/', $route)[0]
                ) {
                self::registerRoute(self::dyn($routeName), $controllerName);
            }

            if (self::isRouteValid()) {
                
                if(file_exists( CONTROLLERS_PATH.$controllerName.'.php' )) {

                    require_once( CONTROLLERS_PATH.$controllerName.'.php' );
                }
            }
        }
    }