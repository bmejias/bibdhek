<!-- File: /app/views/books/add.ctp -->

<h1>Add Book</h1>

<?php
	echo $this->Form->create('Book');
	echo $this->Form->input('title', array('type'=>'text', 'size'=>'30'));
	echo $this->Form->input('author', array('type'=>'text', 'size'=>'30'));
	echo $this->Form->input('cd', array('type'=>'checkbox', 'label'=>'CD'));
	echo $this->Form->input('level', array('type'=>'text', 'size'=>'7'));
	echo $this->Form->select('lang',
							 array('nl'=>'Nederlands',
								   'en'=>'English',
								   'fr'=>'Français',
								   'es'=>'Español',
								   'de'=>'Deutsch'),
							 array('label'=>'Language'));
	echo $this->Form->input('isbn', array('type'=>'text', 'size'=>'13'));
	echo $this->Form->input('copies', array('type'=>'text', 'size'=>'3'));
	echo $this->Form->end('Submit Book');
?>
