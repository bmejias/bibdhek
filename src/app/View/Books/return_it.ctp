<!-- File: /app/views/books/lend.ctp -->

<?php
include_once('../Lib/lib.php');

$fine_str = toCurrency($fine);
?>

<h2><?php echo $book['title']; ?></h2>

<?php
echo $this->Form->create('Loan',
                            array(  'type' => 'post', 
                                    'action'=>'../loans/return_book'));
?>

<table>
    <tr>
        <td>Author</td>
        <td><?php echo $book['author']; ?></td>
    </tr>
    <tr>
        <td>Taal</td>
        <td><?php echo $book['lang']; ?></td>
    </tr>
    <tr>
        <td>Niveau</td>
        <td><?php echo ($book['level'] == '') ? '-' : $book['level']; ?></td>
    </tr>
    <tr>
        <td>Code</td>
        <td><?php echo $copy['code']; ?></td>
    </tr>
    <tr>
        <td>User</td>
        <td><?php echo $student; ?></td>
    </tr>
    <tr>
        <td>Date out</td>
        <td><?php echo $loan['date_out']; ?></td>
    </tr>
    <tr>
        <td>To return on</td>
        <td><?php echo $loan['date_return']; ?></td>
    </tr>
    <tr>
        <td>Date of return</td>
        <td>
            <?php
                echo $this->Form->input('date_in',
                                        array('type'  => 'text',
                                              'label' => '',
                                              'size'  => 10,
                                              'value' => $today));
            ?>
        </td>
    </tr>
    <?php if ($loan['cd']) : ?>
        <tr>
            <td>
                <?php
                    echo $this->Form->input('cd', array('type'  => 'checkbox',
                                                        'label' => 'CD',
                                                        'checked'=> true));
                ?>
            </td>
            <td>
                <?php
                    $deposit = toCurrency($loan['deposit']);
                    echo $this->Form->input('deposit_back',
                                            array('type'  => 'text',
                                                  'label' => 'Deposit',
                                                  'value' => $deposit,
                                                  'size'  => 5));
                ?>
            </td>
        </tr>
    <?php else : ?>
    <?php
        echo $this->Form->hidden('cd', array('value' => 'true'));
        echo $this->Form->hidden('deposit_back', array('value' => "0,00"));
    ?>
    <?php endif; ?>
    <tr>
        <td>Fine</td>
        <td><?php echo $fine_str; ?></td>
    </tr>
    <tr>
        <td>Pay</td>
        <td>
            <?php
                echo $this->Form->input('to_pay',
                                        array('type'    => 'text',
                                              'label'   => '',
                                              'value'   => $fine_str,
                                              'size'    => 5));
            ?>
        </td>   
    </tr>
    <?php
        echo $this->Form->hidden('fine', array('value' => $fine));
        echo $this->Form->hidden('deposit', array('value' => $loan['deposit']));
        echo $this->Form->hidden('book_id', array('value' => $book['id']));
        echo $this->Form->hidden('copy_id', array('value' => $copy['id']));
        echo $this->Form->hidden('loan_id', array('value' => $loan['id']));
        echo $this->Form->hidden('back_to', array('value' => $back_to));
    ?>
</table>

<?php
echo $this->Form->submit('Full Return', array('name' => 'data[Loan][full]'));
echo $this->Form->submit('Only pay fine', array('name' => 'data[Loan][pay]'));
echo $this->Form->submit('Only return book', array('name' => 'data[Loan][return]'));
echo $this->Form->submit('Cancel', array('name' => 'data[Loan][cancel]'));
echo $this->Form->end();
?>

