<form action="pwdchg.php" method="post">
    <fieldset>
        <div class="form-group">
            <input class="form-control" name="password" placeholder="Current Password" type="password"/>
        </div>
        <div class="form-group">
            <input class="form-control" name="newpassword" placeholder="New Password" type="password"/>
        </div>
        <div class="form-group">
            <input class="form-control" name="confirm_newpassword" placeholder="Confirm New Password" type="password"/>
        </div>
        <div class="form-group">
            <button class="btn btn-default" type="submit">
                <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>
                Change Password
            </button>
        </div>
    </fieldset>
</form>
