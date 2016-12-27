<div>
    <form method="post" action="test_manipulate.php"><table id="profile" class="t1">
        
    <tr>
        <th bgcolor='#4CAF50'>Test ID</th>
        <th bgcolor='#4CAF50'>Number of Trials</th>
        <th bgcolor='#4CAF50'>Start Time</th>
        <th bgcolor='#4CAF50'>End Time</th>
        <th bgcolor='#4CAF50'>Status</th>
        <th bgcolor='#4CAF50'>Available Action</th>
    </tr>
    
    <?php if (count($tests) != 0): ?>
        <?php foreach ($tests as $t): ?>

        <tr>
            <td><?= $t["id"] ?></td>
            <td><?= $t["n_trial"] ?></td>
            <td><?= $t["start"] ?></td>
            <td><?= $t["end"] ?></td>
            <td><?= $t["status"] ?></td>
            <td><?= $t["action"] ?></td>
        </tr>
        
        <?php endforeach ?>
    <?php endif ?>
    
    <tr>
        <td align='center' colspan=6><a href="test.php">Start New Test</a></td>
    </tr>
    
    </table></form>
</div>
