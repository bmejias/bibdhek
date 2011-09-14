<!-- File: /app/views/books/add.ctp -->

<h2>Add Book</h2>

<?php
	echo $this->Form->create('Book', array('type'  =>  'post',
										   'action' => 'do_add'));
	echo $this->Form->input('title', array('type' => 'text', 'size' => '30'));
	echo $this->Form->input('author', array('type' => 'text', 'size' => '30'));
	echo $this->Form->input('cd', array('type' => 'checkbox', 'label' => 'CD'));
	echo $this->Form->input('level', array('type' => 'text', 'size' => '7'));
?>
	<label for="BookLang">Language</label>
<?php
	echo $this->Form->select('lang',
							 array('nl' => 'Nederlands',
								   'en' => 'English',
								   'fr' => 'Français',
								   'es' => 'Español',
								   'de' => 'Deutsch'),
							 'nl',
						 	 array(),
						 	 array(),
							 false);
	echo $this->Form->input('isbn', array('type' => 'text', 'size' => '13'));
	echo $this->Form->input('copies', array('type' => 'text', 'size' => '3'));
	echo $this->Form->end('Submit Book');
?>

<br />
<p> <?php echo $this->Html->link('Back to actions', '../pages/admin'); ?> </p>
