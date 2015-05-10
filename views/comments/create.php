<section class="col-xs-12 col-sm-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">New Comment</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" action="/<?php echo htmlspecialchars($this->blogName); ?>/comments/create"
                  method="POST">
                <input type="hidden" name="postId" value="<?php echo htmlspecialchars($this->postId); ?>">
                <input type="hidden" name="formToken" value="<?= $_SESSION['formToken'] ?>"/>

                <div class="form-group">
                    <label for="name" class="col-xs-12 col-sm-2 control-label">Name:</label>

                    <div class="col-xs-12 col-sm-10">
                        <input type="text" class="form-control" id="name" placeholder="Name" name="name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-xs-12 col-sm-2 control-label">Email (opt.):</label>

                    <div class="col-xs-12 col-sm-10">
                        <input type="email" class="form-control" id="email" placeholder="Email" name="email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="text" class="col-xs-12 col-sm-2 control-label">Text:</label>

                    <div class="col-xs-12 col-sm-10">
                        <textarea class="form-control" id="text" placeholder="Content" name="text" rows="5"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10 col-xs-12">
                        <input type="submit" class="btn btn-default" value="Add comment"/>
                        <a href="/<?php echo htmlspecialchars($this->blogName); ?>/posts/index/<?php echo htmlspecialchars($this->postId); ?>">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>