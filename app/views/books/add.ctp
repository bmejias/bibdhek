<!-- File: /app/views/books/add.ctp -->

<h1>Add Book</h1>

<?php
	echo $this->Form->create('Book');
	echo $this->Form->input('title');
	echo $this->Form->input('author');
?>
	CD
<?php
	echo $this->Form->checkbox('cd', array('hiddenField'=>false));
	echo $this->Form->input('level');
	echo $this->Form->select('lang',
							 array('nl'=>'Nederlands',
								   'en'=>'English',
								   'fr'=>'Français',
								   'es'=>'Español',
								   'de'=>'Deutsch'),
							 null,
							 array('width'=>'100'),
							 array(),
							 false);
	echo $this->Form->input('isbn');
	echo $this->Form->end('Submit Book');
?>
