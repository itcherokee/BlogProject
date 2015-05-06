<h1>Post\Index View</h1>

<?php
if ($this->isOwnerOfBlog()) {
    echo "<a href='/" . $this->blogName . "/posts/create'>Add New Post</a>";
}

foreach ($this->posts as $post) {
    echo '<div class="post-box">';
    echo '<div class="post-box-title">' . htmlspecialchars($post['title']) . '</div>';
    echo '<div class="post-box-text">' . htmlspecialchars($post['text']) . '</div>';
    echo '<div class="post-box-date-visits">';
    echo '<span class="post-box-date">Date: ' . htmlspecialchars($post['date']) . '</span>';
    echo '<span class="post-box-visits">Views: ' . htmlspecialchars($post['visits']) . '</span>';
    echo '</div>';
    echo '</div>';
}
?>

<a href="/<?= $this->blogName?>/posts/index?page=<?php echo $this->currentPage - 1; ?>">Back</a>
<a href="/<?= $this->blogName?>/posts/index?page=<?php echo $this->currentPage + 1; ?>">Next</a>