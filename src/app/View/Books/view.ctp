<!-- File: /app/views/books/view.ctp -->
<?php
$form = $this->Form;
?>

<h2><?php echo $book['title']; ?></h2>

<table>
    <tr>
        <td>Author</td>
        <td><?php echo $book['author']; ?></td>
    </tr>
    <tr>
        <td>Taal</td>
        <td><?php echo $book['lang']; ?></td>
    </tr>
</table>

<h3>Copies</h3>
<table>
    <thead>
        <tr>
            <th>code</th>
            <th>status</th>
            <th>student</th>
            <th>return date</th>
            <th></th>
        <tr>
    </thead>
    <tbody>
        <?php foreach ($copies as $copy) : ?>
        <tr>
            <td><?php echo $copy['code']; ?></td>
            <td><?php echo $copy['status']; ?></td>
            <td><?php echo $copy['student']; ?></td>
            <td><?php echo $copy['date_return']; ?></td>
            <td><?php
                if ($copy['status'] == 'available')
                {
                    $action = 'lend';
                    $submit_label = 'lend';
                }
                elseif ($copy['status'] == 'lent')
                {
                    $action = 'return_it';
                    $submit_label = 'return';
                }
                echo $this->Form->create('Book',
                                         array('type' => 'post',
                                               'action' => $action));
                echo $this->Form->hidden('book_id', array('value' => $book['id']));
                echo $this->Form->hidden('copy_id', array('value' => $copy['id']));
                echo $this->Form->hidden('user_id',
                                         array('value' => $copy['user_id']));
                echo $this->Form->hidden('loan_id',
                                         array('value' => $copy['loan_id']));
                echo $this->Form->end($submit_label);
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<br />
<p>
<?php echo $this->Html->link('Back to actions', '../pages/admin'); ?> 
<?php echo $this->Html->link('Back to books', '../books'); ?>
</p>
