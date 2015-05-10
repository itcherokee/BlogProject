<section class="col-xs-12 col-sm-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Registration</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" action="/home/users/register" method="POST">
                <input type="hidden" name="formToken" value="<?= $_SESSION['formToken'] ?>" />
                <div class="form-group">
                    <label for="username" class="col-xs-2 control-label">Username *:</label>

                    <div class="col-xs-12 col-sm-5">
                        <input type="text" class="form-control" id="username" placeholder="Username (more than 4 symbols)" name="username">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-xs-2 control-label">Password *:</label>

                    <div class="col-xs-12 col-sm-5">
                        <input type="password" class="form-control" id="password" placeholder="Password (more than 4 symbols)"
                               name="password">
                    </div>
                    <div class="form-group">
                        <div class="col-xs-offset-2 col-xs-10">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" checked="checked" name="has-blog" disabled> Create blog
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-offset-2 col-xs-10">
                            <input type="submit" class="btn btn-default" value="Register"/>
                            <a href="/home/users/login">Go login</a>
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