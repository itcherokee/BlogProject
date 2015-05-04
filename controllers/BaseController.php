<?php
namespace CONTROLLERS;
abstract class BaseController
{
    public function __construct()
    {

    }

    public function index(){
        echo "I'm BaseController Index Action";
    }

    public function view($partial = false){
        if ($partial){
            // simulate AJAX
        }

       // include 
    }
}