<section class="col-xs-12 col-sm-9">
    <div class="panel panel-default">
        <div class="panel-heading">
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
                            <a href="/<?= $this->blogName ?>/posts/index?page=<?php echo $this->currentPage - 1; ?>">
                                <span>&larr;</span>Newer</a>
                        </li>
                        <li class="next <?php echo $this->currentPage == $this->lastPage ? 'disabled' : '' ?>">
                            <a href="/<?= $this->blogName ?>/posts/index?page=<?php echo $this->currentPage + 1; ?>">Older<span>&rarr;</span></a>
                        </li>
                    </ul>
                </nav>
            <?php endif ?>
        </div>
    </div>
</section>
<aside class="col-xs-12 col-sm-3">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Posts</h3>
        </div>
        <?php
        if ($this->postsHistorically != null) {
            echo '<ul class="list-group">';
            foreach ($this->postsHistorically as $year) {
                echo '<li class="list-group-item">';
                echo $year['year'];
                if ($post['year'])
                echo '</li>';
            }
            echo "</ul>";
        } else {
            echo '<ul class="list-group"><li class="list-group-item">No posts</li></ul>';
        }
        ?>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Most popular tags</h3>
        </div>
        <?php
        if ($this->mostPopularTags != null) {
            echo '<ul class="list-group">';
            foreach ($this->mostPopularTags as $tag) {
                echo '<li class="list-group-item">';
                echo '<span class="badge">' . $tag['counts'] . '</span>';
                echo $tag['name'];
                echo '</li>';
            }
            echo "</ul>";
        } else {
            echo '<ul class="list-group"><li class="list-group-item">No tags</li></ul>';
        }
        ?>
    </div>
</aside>