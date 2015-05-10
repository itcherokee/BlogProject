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
            if (!isset($_POST['formToken']) || $_POST['formToken'] != $_SESSION['formToken']) {
                throw new \Exception('Invalid request!');
                exit;
            }

            $username = $_POST['username'];
            $password = $_POST['password'];
            $login_success = $this->modelData->login($username, $password);
            if ($login_success) {
                $_SESSION['username'] = $username;
                $this->addInfoMessage("Login success");
            } else {
                $this->addErrorMessage("Login error");
                $this->redirect(SYSTEM_BLOG, $this->controllerName, "login");
            }

            $this->redirect($username, 'posts', DEFAULT_ACTION);
        }

        $_SESSION['formToken'] = uniqid(mt_rand(), true);
        $this->renderView();
    }

    public function register()
    {
        $this->actionName = __FUNCTION__;

        if ($this->isPost) {
            if (!isset($_POST['formToken']) || $_POST['formToken'] != $_SESSION['formToken']) {
                throw new \Exception('Invalid request!');
                exit;
            }

            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            // TODO: to be modified when site start to support users without blogs
            $hasBlog = true;

            if (!empty($username) && !empty($password)) {
                if (strlen($username) > 2 && strlen($password) > 4) {
                    $registration_success = $this->modelData->register($username, $password, $hasBlog);
                    if ($registration_success) {
                        $_SESSION['username'] = $username;
                        $this->addInfoMessage("Successful registration");
                    } else {
                        $this->addErrorMessage("Registration error!");
                        $this->redirect(SYSTEM_BLOG, $this->controllerName, $this->actionName);
                    }

                    $this->redirect($username, 'posts', DEFAULT_ACTION);
                } else {
                    $this->addErrorMessage("Registration error - Username & Password cannot be less than 4 symbols!");
                }
            } else {
                $this->addErrorMessage("Registration error - none of fields cannot be empty!");
            }

            $this->redirect(SYSTEM_BLOG, $this->controllerName, $this->actionName);
        }

        $_SESSION['formToken'] = uniqid(mt_rand(), true);
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