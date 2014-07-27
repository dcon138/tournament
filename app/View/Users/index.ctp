<a class="btn btn-primary pull-right" href="<?= Router::url(array('action' => 'add')) ?>">Add User</a>

<?php if (empty($users)): ?>
    <p>There are no users that match your current filters.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Display Name</th>
                <th>Email</th>
                <!-- if its an admin -->
                    <th>Actions</th>
                <!-- endif -->
            </tr>
        </thead>
        <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['User']['firstname'] ?> <?= $user['User']['lastname'] ?></td>
            <td><?= $user['User']['displayName'] ?></td>
            <td><?= $user['User']['email'] ?></td>
            <!-- if its an admin -->
                <td><!-- actions to go here --></td>
            <!-- endif -->
        </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>