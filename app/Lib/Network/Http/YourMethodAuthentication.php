<?php
class YourMethodAuthentication {

/**
 * Authentication
 *
 * @param HttpSocket $http
 * @param array $authInfo
 * @return void
 */
    public static function authentication(HttpSocket $http, $access_token) {
        // Do something, for example set $http->request['header']['Authentication'] value
        //$http->request['header']['Authentication'] = 'Bearer '; // + $access_token;
    }

}