<section class="col-xs-12 col-sm-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <?php
                echo "<strong><a href='/" . htmlspecialchars($this->blogName) . "/posts/index'>";
                echo htmlspecialchars(ucfirst($this->blogName));
                echo "</a></strong>'s blog";
                if ($this->isOwnerOfBlog()) {
                    echo " <a href='/" . htmlspecialchars($this->blogName)
                        . "/posts/create' class='btn btn-default btn-xs'>Add New Post</a>";
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
                echo "<a href='/" . htmlspecialchars($this->blogName) . "/posts/index/"
                    . htmlspecialchars($post['id']) . "'> ";
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
                    echo "<a href='/" . htmlspecialchars($this->blogName) . "/posts/edit/"
                        . htmlspecialchars($post['id']) . "'> ";
                    echo "<span class='glyphicon glyphicon-pencil'> </span></a>";
                    echo "<a href='/" . htmlspecialchars($this->blogName) . "/posts/delete/"
                        . htmlspecialchars($post['id']) . "'> ";
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
                if ($this->isSinglePost) {

                    echo '<div class="panel panel-default">';
                    echo '<div class="panel-heading">';
                    echo '<h3 class="panel-title">Comments: ';
                    echo " <a href='/" . htmlspecialchars($this->blogName) . "/comments/create/"
                        . htmlspecialchars($post['id']) . "' class='btn btn-default btn-xs'>Add New Comment</a>";
                    echo ' </h3>';
                    echo '</div>'; // close heading

                    echo '<div class="panel-body small">';
                    if (!empty($post['comments'])) {
                        foreach ($post['comments'] as $comment) {
                            echo '<div>';
                            echo htmlspecialchars($comment['text']);
                            echo '</div>';
                            echo '<div>';
                            echo '<span>written by [' . htmlspecialchars($comment['username']) . ']</span> ';
                            if ($this->isOwnerOfBlog()) {
                                echo "<a href='/" . htmlspecialchars($this->blogName) . "/comments/edit/"
                                    . htmlspecialchars($comment['id']) . '/'. htmlspecialchars($post['id']) . "'> ";
                                echo "<span class='glyphicon glyphicon-pencil'> </span></a>";
                                echo "<a href='/" . htmlspecialchars($this->blogName) . "/comments/delete/"
                                    . htmlspecialchars($comment['id']) . '/'. htmlspecialchars($post['id']) .  "'> ";
                                echo "<span class='glyphicon glyphicon-trash'></span></a>";
                            }
                            echo '</div>';
                            echo '<hr/>';
                        }
                    }
                    echo '</div>'; // close body
                    echo '</div>'; // close panel
                }
            }
            ?>

            <?php if (!$this->isSinglePost): ?>
                <nav>
                    <ul class="pager small">
                        <li class="previous <?= $this->currentPage == $this->firstPage ? 'disabled' : '' ?>">
                            <a href="/<?= htmlspecialchars($this->blogName); ?>/posts/index?page=<?= htmlspecialchars($this->currentPage - 1);
                            echo empty($this->historyPeriod) ? "" : "&" . htmlspecialchars($this->historyPeriod); ?>">
                                <span>&larr;</span>Newer</a>
                        </li>
                        <li class="next <?= $this->currentPage == $this->lastPage ? 'disabled' : '' ?>">
                            <a href="/<?= htmlspecialchars($this->blogName); ?>/posts/index?page=<?= htmlspecialchars($this->currentPage + 1);
                            echo empty($this->historyPeriod) ? "" : "&" . htmlspecialchars($this->historyPeriod); ?>">
                                Older<span>&rarr;</span></a>
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
        <?php if ($this->historyList != null) : ?>
            <div class="panel-body">
                <div class="panel-group" id="accordion">
                    <?php $isFirstPanel = true; ?>
                    <?php foreach ($this->historyList as $key => $year) : ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"
                                       href="#collapse<?= htmlspecialchars($key) ?>">
                                        <em><?= htmlspecialchars($key); ?></em>
                                    </a>
                                </h4>
                            </div>

                            <div id="collapse<?= htmlspecialchars($key) ?>"
                                 class="panel-collapse collapse <?= $isFirstPanel ? 'in' : '';
                                 $isFirstPanel = false ?>">
                                <ul class="list-group small">
                                    <?php foreach ($year as $month => $counts) : ?>
                                        <li class="list-group-item">
                                            <span class="badge small"><?= htmlspecialchars($counts); ?></span>
                                            <?php
                                            $dateObj = DateTime::createFromFormat('!m', $month);
                                            $monthName = $dateObj->format('F');
                                            ?>
                                            <a href="/<?= htmlspecialchars($this->blogName) ?>/posts/index?year=<?= htmlspecialchars($key); ?>&month=<?= htmlspecialchars($month); ?>">
                                                <?= htmlspecialchars($monthName); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>

                    <?php endforeach; ?>

                </div>
            </div>
        <?php else: ?>
            <ul class="list-group">
                <li class="list-group-item">No posts</li>
            </ul>
        <?php endif; ?>
    </div>
    <form role="search" method="POST" action="/<?= htmlspecialchars($this->blogName); ?>/posts/search">
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search by tag..." name="tag">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">Go!</button></span>
            </div>
        </div>
    </form>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Most popular tags</h3>
        </div>
        <?php
        if ($this->mostPopularTags != null) {
            echo '<ul class="list-group">';
            foreach ($this->mostPopularTags as $tag) {
                echo '<li class="list-group-item">';
                echo '<span class="badge">' . htmlspecialchars($tag['counts']) . '</span>';
                echo htmlspecialchars($tag['name']);
                echo '</li>';
            }
            echo "</ul>";
        } else {
            echo '<ul class="list-group"><li class="list-group-item">No tags</li></ul>';
        }
        ?>
    </div>
</aside>