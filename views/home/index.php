<h3 class="panel-title">Users Blogs</h3>
</div>
<div class="panel-body">

<?php
if ($this->blogs != null) {
    echo '<div class="row">';
    //echo '<div class="blog-box row">';
    foreach ($this->blogs as $blog) {
        echo '<div class="col-xs-6 col-sm-3">';

        echo '<div class="thumbnail">';
        echo '<a href="/' . htmlspecialchars($blog['username']) . '/posts/index">';
        echo '<img class="img-thumbnail" src="/content/blog.png">';
        echo '<div class="caption text-center">';
        echo '<h3 class="blog-box-blogname">' . htmlspecialchars($blog['username']) . "'s Blog</h3>";
        echo '</div>';

        echo '</a>';

        echo '</div>';
        echo '</div>';


    }
   // echo '</div>';
    echo '</div>';
} else {
    echo 'No blogs';
}
?>

<nav>
    <ul class="pager">
        <li class="previous <?php echo $this->currentPage == $this->firstPage ? 'disabled' : '' ?>">
            <a href="/<?= $this->blogName ?>/home/index?page=<?php echo $this->currentPage - 1; ?>"><span>&larr;</span>
                Newer</a>
        </li>
        <li class="next <?php echo $this->currentPage == $this->lastPage ? 'disabled' : '' ?>">
            <a href="/<?= $this->blogName ?>/home/index?page=<?php echo $this->currentPage + 1; ?>">Older
                <span>&rarr;</span></a>
        </li>
    </ul>
</nav>