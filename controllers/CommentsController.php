<?php
namespace CONTROLLERS;
class CommentsController extends BaseController
{
    protected $postId = null;

    public function __construct($blog)
    {
        parent::__construct($blog);
    }

    public function create($postId)
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
                $commentId = $this->modelData->createComment($text, $name, $email, $post_id);
                $params[] = $post_id;
                if ($commentId > 0) {
                    $this->addInfoMessage("Comment added.");

                    $this->redirect($this->blogName, 'Posts', DEFAULT_ACTION, $params);
                } else {
                    $this->addErrorMessage("Error adding comment.");
                    $this->redirect($this->blogName, 'Posts', DEFAULT_ACTION, $params);
                }
            } else {
                $this->addErrorMessage("Name & Text must have a value!");
               // $this->postId = $post_id;
            }
        } else {
            $this->postId = $postId[0];
        }

        $_SESSION['formToken'] = uniqid(mt_rand(), true);
        $this->renderView();
    }

    public function edit($params)
    {

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

        $post[] = $post_id;
        $this->redirect($this->blogName, 'Posts', DEFAULT_ACTION, $post);
    }
}