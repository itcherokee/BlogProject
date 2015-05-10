<?php
namespace CONTROLLERS;
class CommentsController extends BaseController
{
    protected $postId = null;

    public function __construct($blog_name)
    {
        parent::__construct($blog_name);
    }

    public function create($params)
    {
        $this->actionName = __FUNCTION__;

        if ($this->isPost) {
            if (!isset($_POST['formToken']) || $_POST['formToken'] != $_SESSION['formToken']) {
                throw new \Exception('Invalid request!');
                exit;
            }

            $name = trim($_POST['name']);
            $text = trim($_POST['text']);
            $email = trim($_POST['email']);
            $post_id = trim($_POST['postId']);
            $this->postId = $post_id;

            if (!empty($name) && !empty($text) & !empty('postId')) {
                $comment_id = $this->modelData->createComment($text, $name, $email, $post_id);
                $parameters[] = $post_id;
                if ($comment_id > 0) {
                    $this->addInfoMessage("Comment added.");

                    $this->redirect($this->blogName, 'Posts', DEFAULT_ACTION, $parameters);
                } else {
                    $this->addErrorMessage("Error adding comment.");
                    $this->redirect($this->blogName, 'Posts', DEFAULT_ACTION, $parameters);
                }
            } else {
                $this->addErrorMessage("Name & Text must have a value!");
            }
        } else {
            $this->postId = $params[0];
        }

        $_SESSION['formToken'] = uniqid(mt_rand(), true);
        $this->renderView();
    }

    public function edit($params)
    {
        $this->authorize();
        $comment_id = $params[0];
        $this->postId = $params[1];
        $this->actionName = __FUNCTION__;
        $this->comment = $this->modelData->getCommentById($comment_id)[0];

        if ($this->isPost) {
            if (!isset($_POST['formToken']) || $_POST['formToken'] != $_SESSION['formToken']) {
                throw new \Exception('Invalid request!');
                exit;
            }

            $username = trim($_POST['username']);
            $useremail = trim($_POST['useremail']);
            $text = trim($_POST['text']);

            if (!empty($username) && !empty($text)) {
                if ($this->modelData->updateComment($comment_id, $username, $useremail, $text) > 0) {
                    $this->addInfoMessage("Comment edited.");
                    $parameters[] = $this->postId;
                    $this->redirect($this->blogName, 'posts', DEFAULT_ACTION, $parameters);
                } else {
                    $this->addErrorMessage("Error editing comment.");
                }
            } else {
                $this->addErrorMessage("Username & Text fields must have a value!");
            }
        }

        $_SESSION['formToken'] = uniqid(mt_rand(), true);
        $this->renderView();
    }

    public function delete($params)
    {
        $this->authorize();
        $comment_id = $params[0];
        $post_id = $params[1];
        $this->actionName = __FUNCTION__;

        if ($this->modelData->deleteComment($comment_id)) {
            $this->addInfoMessage("Comment deleted.");
        } else {
            $this->addErrorMessage("Error deleting comment");
        }

        $parameters[] = $post_id;
        $this->redirect($this->blogName, 'Posts', DEFAULT_ACTION, $parameters);
    }
}