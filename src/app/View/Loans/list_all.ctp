<?php
include_once('../Lib/lib.php');
?>
<h2>List of all loans</h2>
<table>
    <thead>
        <tr>
            <th>User</th>
            <th>Due date</th>
            <th>Book</th>
            <th>Author</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($the_loans as $loan): ?>
            <tr>
                <td><?php echo $loan['user']; ?></td>
                <td><?php echo $loan['due']; ?></td>
                <td><?php echo $loan['book']; ?></td>
                <td><?php echo $loan['author']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
