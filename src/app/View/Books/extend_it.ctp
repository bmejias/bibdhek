<?php
/**
 * Author: Boriss Mejias <tchorix@gmail.com>
 */

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
        <td><?php echo Book::$LANGS[$book['lang']]; ?></td>
    </tr>
</table>

<h3>Extend Loan</h3>

<table>
    <thead>
        <tr>
            <th>code</th>
            <th>status</th>
            <th>student</th>
            <th>return date</th>
            <th>new date</th>
            <th></th>
        <tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo $copy['code']; ?></td>
            <td><?php echo $copy['status']; ?></td>
            <td><?php echo $copy['student']; ?></td>
            <td><?php echo $copy['date_return']; ?></td>
            <td>
            <?php
                $copy_available = $copy['status'] == Copy::$AVAILABLE;
                $copy_lent = $copy['status'] == Copy::$LENT;
                if ($cart_mode)
                {
                    if ($copy_available)
                    {
                    ?>
                    <a href="../cart/add?copy_id=<?php echo $copy['id']; ?>">
                        <img src="../img/add_to_bag.png" height="30" align="left">
                    </a>
                <?php
                    }
                }
                else
                {
                    // Default action
                    if ($copy_available)
                    {
                        $action = 'lend';
                        $submit_label = 'lend';
                    }
                    elseif ($copy_lent)
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
                    echo $this->Form->submit($submit_label,
                                             array('name' => $submit_label,
                                                   'id' => $submit_label));
                    // If lent, add "more options"
                    if ($copy_lent)
                    {
                        echo $this->Form->submit('extend',
                                                 array('name' => 'extend',
                                                       'id' => 'extend'));
                    }
                    echo $this->Form->end();

                }
            ?>
            </td>
        </tr>
    </tbody>
</table>

<br />
<p>
<?php echo $this->Html->link('Back to actions', '../pages/admin'); ?> 
<?php echo $this->Html->link('Back to books', '../books'); ?>
</p>
