<?php
namespace CONTROLLERS;
class UsersController extends BaseController
{
    public function __construct()
    {
        parent::__construct(null);
    }

    public function index()
    {
        $this->redirect(SYSTEM_BLOG, $this->controllerName, 'login');
    }

    public function login()
    {
        $this->actionName = __FUNCTION__;
        if ($this->isPost) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $loginSuccess = $this->modelData->login($username, $password);
            if ($loginSuccess) {
                //$this->session->username = $username;
                $_SESSION['username'] = $username;
                $this->addInfoMessage("Login success");
            } else {
                $this->addErrorMessage("Login error");
                $this->redirect(SYSTEM_BLOG, "users", "login");
            }

            $this->redirect($username, 'posts', DEFAULT_ACTION);
        }

        $this->renderView();
    }

    public function register()
    {
        $this->actionName = __FUNCTION__;
        if ($this->isPost) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // TODO: to be modified when site start to support users without blogs
            $hasBlog = true;
//            if (!empty($_POST['has-blog'])){
//                $hasBlog = true;
//            }

            if (!empty($username) && !empty($password)) {
                //$registrationSuccess = $this->modelData->register($username, $password, $blogName);
                $registrationSuccess = $this->modelData->register($username, $password, $hasBlog);
                if ($registrationSuccess) {
                    $_SESSION['username'] = $username;
                    $this->addInfoMessage("Successful registration");
                } else {
                    $this->addErrorMessage("Registration error!");
                    $this->redirect(SYSTEM_BLOG, $this->controllerName, $this->actionName);
                }

                $this->redirect($username, 'posts', DEFAULT_ACTION);
            }

            $this->addErrorMessage("Registration error - none of fields cannot be empty!");
            $this->redirect(SYSTEM_BLOG, $this->controllerName, $this->actionName);
        }

        $this->renderView();
    }

    public function logout()
    {
        $this->authorize();

        if ($this->isPost) {
            session_destroy();
            session_start();
            $this->addInfoMessage("You are logged out!");
            $this->redirect(SYSTEM_BLOG, DEFAULT_CONTROLLER, DEFAULT_ACTION);
        }
    }
}