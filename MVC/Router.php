<?php

namespace MVC;
class Router
{
    private $controller = null;
    private $action = null;
    private $params = null;

    public function parse()
    {
        if ($_SERVER['REQUEST_URI']) {
            $uri = substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), 1);
            $params = preg_split('/\//', $uri, -1, PREG_SPLIT_NO_EMPTY);
            if ($params != null && count($params) > 0) {
                $this->controller = ucfirst($params[0]);
                if (count($params) > 1) {
                    $this->action = strtolower($params[1]);
                    unset($params[0], $params[1]);
                    $this->params = array_values($params);
                }
            }

            echo $this->controller . '<br/>' . $this->action . '<br/>' . implode(', ', $this->params);
        } else {
            throw new \Exception('No REQUEST_URI set.');
        }
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getParams()
    {
        return $this->params;
    }
}