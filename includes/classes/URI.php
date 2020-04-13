<?php

    class URI {

        public static function get($params) {

            if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
                return explode('&', explode($params.'=', $_SERVER['REQUEST_URI'])[1])[0];
            } else {
                return false;
            }
        }
    }