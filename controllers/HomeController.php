<?php
namespace CONTROLLERS;
class HomeController extends BaseController{
    public function __construct(){
        parent::__construct();
    }

    public function Index(){
        echo "I'm Index method in HomeController";
    }

    public function Test(){
        echo "I'm Test method in HomeController";
    }

}