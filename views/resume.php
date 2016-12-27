<form action="test.php" method="post">
    <div>
        You are about to resume test #<?= $_SESSION["test_id"] ?> at trial #<?= ($_SESSION["seq"] + 1) ?> .
        <br>
        <br>
        <br>
    </div>
    <fieldset>
        <div class="form-group">
            <button class="btn btn-default" type="submit">
                <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>
                Resume Test
            </button>
        </div>
    </fieldset>
</form>
