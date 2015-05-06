<?php

namespace MVC;
class Session
{
    public function __construct($name, $lifetime = 3600, $path = '/', $domain = null, $secure = false){
        if (strlen($name) < 1){
            $name = 'blog_session';
        }

        session_name($name);
        session_set_cookie_params($name, $lifetime, $path, $domain, true);
        session_start();
    }

    public function __get($name){
        return $_SESSION[$name];
    }

    public function __set($name, $value){
        $_SESSION[$name] = $value;
    }

    public function closeSession(){
        session_destroy();
    }

    public function getSession(){
        return session_id();
    }

    public function saveSession(){
        session_write_close();
    }
}