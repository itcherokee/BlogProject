<h1>Users Blogs</h1>

<?php

foreach ($this->blogs as $blog) {
    echo '<div class="blog-box">';
    echo '<img class="blog-box-image" src="/content/blog.png">';
    echo '<span class="blog-box-blogname">' . htmlspecialchars($blog['username']) . '</span>';
    echo '</div>';
}
?>

<a href="/<?= $this->blogName?>/home/index?page=<?php echo $this->currentPage - 1; ?>">Back</a>
<a href="/<?= $this->blogName?>/home/index?page=<?php echo $this->currentPage + 1; ?>">Next</a>