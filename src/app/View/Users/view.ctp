<?php
include_once('../Lib/lib.php');
?>
<h2><?php echo $user['first_name']." ".$user['last_name']; ?></h2>

<?php
echo $this->Form->create('User', array('type'   => 'post', 
                                       'action' => 'bulk_return'));
?>
<table>
    <thead>
        <tr>
            <th></th>
            <th>Title</th>
            <th>Date to return</th>
            <th>Status</th>
            <th>Fine</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($loans as $loan) : ?>
        <tr>
            <td>
                <?php
                    echo $this->Form->input($loan['id'],
                                            array('type' => 'checkbox',
                                                  'label' => ''));
                ?>
            </td>
            <td>
                <?php
                    /* to return the book, we need: book_id, user_id, 
                     * copy_id, loan_id 
                     */
                    $url = '../books/return_it';    
                    $url.= '?book_id='.$loan['book_id'];
                    $url.= '&copy_id='.$loan['copy_id'];
                    $url.= '&user_id='.$loan['user_id'];
                    $url.= '&loan_id='.$loan['id'];
                    $url.= '&back_to=user';
                    echo $this->Html->link($loan['book_title'], $url); 
                ?>
            </td>
            <td><?php echo $loan['date_return']; ?></td>
            <td><?php echo $loan['status']; ?></td>
            <td><?php echo toCurrency($loan['fine'] - $loan['paid']); ?></td>
            <td><?php if ($loan['cd']) echo "cd"; ?></td>
            
        </tr>
        <?php endforeach; ?>

    </tbody>
</table>

<p>
<?php echo $this->Form->end('Return'); ?>
</p>


<h3>Money Summary</h3>

<table>
    <tbody>
        <tr>
            <td colspan="100%">
                Total fine: <b><?php echo toCurrency($total_fine); ?></b>
            </td>
        </tr>
        <tr>
            <td colspan="100%">
                Total deposit: <b><?php echo toCurrency($total_deposit); ?></b>
            </td>
        </tr>
        <tr>
            <td colspan="100%">
        <?php
            // TODO: Find the translation for saldo
            $saldo = $total_deposit - $total_fine;
            if ($saldo == 0):
        ?>
                There is no money issues with this user
        <?php elseif ($saldo < 0): ?>
                The user needs to pay <?php echo toCurrency($saldo); ?> euro.
        <?php else: ?>
        The biblioteque needs to give <?php echo toCurrency($saldo); ?> euro back to the user
        <?php endif; ?>
            </td>
        </tr>
    </tbody>
</table>
