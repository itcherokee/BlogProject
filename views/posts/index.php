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
                if ($this->isSinglePost){
                    echo " <a href='/" . $this->blogName . "/comments/create/" . htmlspecialchars($post['id']) ." class='btn btn-default btn-xs'>Add New Comment</a>";

                    echo '<div class="panel panel-default">';
                    echo '<div class="panel-heading">';
                    echo '<h3 class="panel-title">Comments:</h3>';
                    echo '</div>'; // close heading

                    echo '<div class="panel-body small">';
                    foreach($post['comments'] as $comment){
                        echo '<div>';
                        echo htmlspecialchars($comment['text']);
                        echo '</div>';
                        echo '<div>';
                        echo '<span>written by [' . htmlspecialchars($comment['username']) . ']</span> ';
                        if ($this->isOwnerOfBlog()) {
                            echo "<a href='/" . $this->blogName . "/comments/edit/" . htmlspecialchars($comment['id']) . "'> ";
                            echo "<span class='glyphicon glyphicon-pencil'> </span></a>";
                            echo "<a href='/" . $this->blogName . "/comments/delete/" . htmlspecialchars($comment['id']) . "'> ";
                            echo "<span class='glyphicon glyphicon-trash'></span></a>";
                        }
                        echo '</div>';
                        echo '<hr/>';
                    }
                    echo '</div>'; // close body

                    echo '</div>'; // close panel
                }

            }
            ?>

            <?php if (!$this->isSinglePost): ?>
                <nav>
                    <ul class="pager small">
                        <li class="previous <?php echo $this->currentPage == $this->firstPage ? 'disabled' : '' ?>">
                            <a href="/<?= $this->blogName ?>/posts/index?page=<?php echo $this->currentPage - 1;
                            echo empty($this->historyPeriod) ? "" : "&" . $this->historyPeriod; ?>">
                                <span>&larr;</span>Newer</a>
                        </li>
                        <li class="next <?php echo $this->currentPage == $this->lastPage ? 'disabled' : '' ?>">
                            <a href="/<?= $this->blogName ?>/posts/index?page=<?php echo $this->currentPage + 1;
                            echo empty($this->historyPeriod) ? "" : "&" . $this->historyPeriod; ?>">
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
                                       href="#collapse<?php echo $key ?>">
                                        <em><?php echo $key ?></em>
                                    </a>
                                </h4>
                            </div>

                            <div id="collapse<?php echo $key ?>"
                                 class="panel-collapse collapse <?php echo $isFirstPanel ? 'in' : '';
                                 $isFirstPanel = false ?>">
                                <ul class="list-group small">
                                    <?php foreach ($year as $month => $counts) : ?>
                                        <li class="list-group-item">
                                            <span class="badge small"><?php echo $counts ?></span>
                                            <?php
                                            $dateObj = DateTime::createFromFormat('!m', $month);
                                            $monthName = $dateObj->format('F');
                                            ?>
                                            <a href="/<?= $this->blogName ?>/posts/index?year=<?php echo $key; ?>&month=<?php echo $month ?>">
                                                <?php echo $monthName; ?>
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
    <form role="search" method="POST" action="/<?php echo $this->blogName ?>/posts/search">
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