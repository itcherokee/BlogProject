<h3 class="panel-title">
    <?php
    echo "<strong><a href='/" . $this->blogName . "/posts/index'>";
    echo ucfirst($this->blogName);
    echo "</a></strong>'s blog";
    if ($this->isOwnerOfBlog()) {
        echo " <a href='/" . $this->blogName . "/posts/create' class='btn btn-default btn-xs'>Add New Post</a>";
    }
    ?>
</h3>
</div>
<div class="panel-body">

    <?php
    foreach ($this->posts as $post) {
        echo '<div class="panel panel-default">';

        echo '<div class="panel-heading">';
        echo '<h3 class="panel-title">';
        echo "<a href='/" . $this->blogName . "/posts/index/" . htmlspecialchars($post['id']) . "'> ";
        echo htmlspecialchars($post['title']);
        echo '</a>';
        echo '</h3>';
        echo '</div>'; // close heading

        echo '<div class="panel-body small">';

        echo '<div>';
        echo htmlspecialchars($post['text']);
        echo '</div>';
        echo '<div>';
        echo '<span class="">[' . htmlspecialchars($post['date']) . ']</span> ';
        if ($this->isOwnerOfBlog()) {
            echo "<a href='/" . $this->blogName . "/posts/edit/" . htmlspecialchars($post['id']) . "'> ";
            echo "<span class='glyphicon glyphicon-pencil'> </span></a>";
            echo "<a href='/" . $this->blogName . "/posts/delete/" . htmlspecialchars($post['id']) . "'> ";
            echo "<span class='glyphicon glyphicon-trash'></span></a>";
        }
        echo '</div>';
        echo '</div>'; // close body

        echo '<div class="panel-footer small">';
        echo '<span class="badge">' . htmlspecialchars($post['visits']) . ' views</span> ';
        echo '<span class="text-left">';
        echo ' Tags: ';
        echo !empty($post['tags']) ? ' <em>' . htmlspecialchars($post['tags']) . '</em>' : '';
        echo '</span>';
        echo '</div>'; // close footer
        echo '</div>'; // close panel
    }
    ?>

    <?php if (!$this->isSinglePost): ?>
    <nav>
        <ul class="pager small">
            <li class="previous <?php echo $this->currentPage == $this->firstPage ? 'disabled' : '' ?>">
                <a href="/<?= $this->blogName ?>/posts/index?page=<?php echo $this->currentPage - 1; ?>"><span>&larr;</span>
                    Older</a>
            </li>
            <li class="next <?php echo $this->currentPage == $this->lastPage ? 'disabled' : '' ?>">
                <a href="/<?= $this->blogName ?>/posts/index?page=<?php echo $this->currentPage + 1; ?>">Newer<span>&rarr;</span></a>
            </li>
        </ul>
    </nav>
<?php endif ?>