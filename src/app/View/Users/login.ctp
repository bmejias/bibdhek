<!-- File: /app/views/users/login.ctp -->

<?php
    echo $this->Form->create('login', array('action' => '../users/check_login'));
?>
<p> <?php echo $msg; ?> </p>

<table>
    <tr>
        <td>
            <?php
                echo $this->Form->input('username', array('label' => 'Username'));
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php
                echo $this->Form->input('password', array('type' => 'password'));
            ?>
        </td>
    </tr>
</table>
<?php
    echo $this->Form->end('Sing in');
?>
