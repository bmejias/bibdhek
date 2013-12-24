<h2>Edit User</h2>

<?php
// at the moment, we will just implement the simple view
echo $this->Form->create('User');
echo $this->Form->input('first_name', array('type'=>'text',
                                            'value'=>  
                                            'size'=>'30'));
echo $this->Form->input('last_name', array('type'=>'text', 'size'=>'30'));
echo $this->Form->input('mode', array('type'=>'hidden', 'value'=>$mode));
echo $this->Form->submit('Edit User', array('name'=>'data[User][edit]'));
echo $this->Form->submit('Cancel', array('name'=>'data[User][From]'));
echo $this->Form->end();
?>
