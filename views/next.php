<div>
    <form method="post" action="test.php">
    <table class="t1">
        <tr>
            <th id='trial' bgcolor='#4CAF50' colspan=3>Trial <?= $_SESSION["seq"] ?> of <?= $_SESSION["N"] ?></th>
        </tr>
        <tr>
            <td colspan=3 bgcolor='#4CAF50'><button type="submit" name="submit" value="next"><?= $buttontext ?></button></td>
        </tr>
    </table></form>
</div>
