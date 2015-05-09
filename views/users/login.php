<h3 class="panel-title">Login</h3>
</div>
<div class="panel-body">
    <form class="form-horizontal" action="/home/users/login" method="POST">
        <div class="form-group row">
            <label for="username" class="col-xs-2 control-label">Username:</label>

            <div class="col-xs-12 col-sm-5">
                <input type="text" class="form-control" id="username" placeholder="Username" name="username">
            </div>
        </div>
        <div class="form-group row">
            <label for="password" class="col-xs-2 control-label">Password:</label>

            <div class="col-xs-12 col-sm-5">
                <input type="password" class="form-control" id="password" placeholder="Password" name="password">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" class="btn btn-default" value="Login"/>
                <a href="/home/users/register">Go register</a>
            </div>
        </div>
    </form>