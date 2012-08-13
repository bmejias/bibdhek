<!-- File: /app/views/users/add.ctp -->

<h2>Add User</h2>

<?php
echo $this->Form->create('User');
if ($mode == 'detailed')
{
    echo $this->Form->input('username', array('type'=>'text', 'size'=>'30'));
    echo $this->Form->input('first_name', array('type'=>'text', 'size'=>'30'));
    echo $this->Form->input('last_name', array('type'=>'text', 'size'=>'30'));
    echo $this->Form->input('email', array('type'=>'text', 'size'=>'30'));
    echo $this->Form->input('password', array('type'=>'password', 'size'=>'30'));
    echo $this->Form->input('confirm', array('type'=>'password', 'size'=>'30'));
}
else
{
    echo $this->Form->input('first_name', array('type'=>'text', 'size'=>'30'));
    echo $this->Form->input('last_name', array('type'=>'text', 'size'=>'30'));
}
echo $this->Form->input('mode', array('type'=>'hidden', 'value'=>$mode));
echo $this->Form->end('Create User');
?>

<br />
<p> <?php echo $this->Html->link('Back to actions', '../pages/admin'); ?> </p>
