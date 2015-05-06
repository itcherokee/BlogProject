<h1>Create New Post</h1>

<form method="post" action="/<?php echo $this->blogName?>/posts/create">
    Title: <input type="text" name="title">
    <br/>
    Text: <input type="text" name="text">
    <br/>
    Tags: <input type="text" name="tags">
    <br/>
    <input type="submit" value="Create">
</form>