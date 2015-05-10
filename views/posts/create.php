<section class="col-xs-12 col-sm-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">New Post</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" action="/<?php echo htmlspecialchars($this->blogName); ?>/posts/create" method="POST">
                <input type="hidden" name="formToken" value="<?= $_SESSION['formToken'] ?>" />
                <div class="form-group">
                    <label for="title" class="col-xs-12 col-sm-2 control-label">Title *:</label>

                    <div class="col-xs-12 col-sm-10">
                        <input type="text" class="form-control" id="title" placeholder="Title" name="title">
                    </div>
                </div>
                <div class="form-group">
                    <label for="text" class="col-xs-12 col-sm-2 control-label">Text *:</label>

                    <div class="col-xs-12 col-sm-10">
                        <textarea class="form-control" id="text" placeholder="Content" name="text" rows="5"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tags" class="col-xs-12 col-sm-2 control-label">Tags *:</label>

                    <div class="col-xs-12 col-sm-10">
                        <input type="text" class="form-control" id="tags" placeholder="Tags" name="tags">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10 col-xs-12">
                        <input type="submit" class="btn btn-default" value="Create"/>
                        <a href="/<?php echo htmlspecialchars($this->blogName); ?>/posts/index">Cancel</a>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10 col-xs-12">
                        <span>* Mandatory</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
