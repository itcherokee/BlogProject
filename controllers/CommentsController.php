<?php
namespace CONTROLLERS;
class CommentsController extends BaseController
{
    public function __construct()
    {
        parent::__construct(null);
    }

    public function index()
    {
        $this->redirect(SYSTEM_BLOG, $this->controllerName, 'login');
    }

    public function create()
    {

    }

    public function edit($params)
    {

    }

    public function delete($id)
    {

    }
}