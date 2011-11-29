<?php
include_once('../libs/lib.php');
?>
<h2><?php echo $user['first_name']." ".$user['last_name']; ?></h2>

<table>
    <thead>
        <tr>
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
                    // I need book_id, user_id, copy_id, loan_id
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


        </tr>
    </tbody>
</table>
