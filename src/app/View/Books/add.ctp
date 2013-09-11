<!-- File: /app/views/books/add.ctp -->

<h2>Add Book</h2>

<?php

$langs =  array('nl' => 'Nederlands',
                'en' => 'English',
                'fr' => 'Français',
                'es' => 'Español',
                'de' => 'Deutsch');

echo $this->Form->create('Book', array('type' => 'post', 'action' => 'do_add'));
echo $this->Form->input('title', array('type' => 'text', 'size' => '30'));
echo $this->Form->input('author', array('type' => 'text', 'size' => '30'));
echo $this->Form->input('cd', array('type' => 'checkbox', 'label' => 'CD'));
echo $this->Form->input('level', array('type' => 'text', 'size' => '7'));
echo $this->Form->input('lang', array('label' => 'Language', 'options' => $langs));
echo $this->Form->input('isbn', array('type' => 'text', 'size' => '13'));
echo $this->Form->input('copies', array('type' => 'text', 'size' => '3'));
echo $this->Form->end('Submit Book');

?>
