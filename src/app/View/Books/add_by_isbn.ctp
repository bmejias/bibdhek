<!-- File: /app/views/books/add_by_isbn.ctp -->

<h2>Add Book by ISBN</h2>

<?php
$form = $this->Form;

echo $this->Form->create('Book', array('type' => 'post', 'action' => 'use_isbn'));
echo $this->Form->input('ISBN', array('type' => 'text', 'size' => '13'));
echo $this->Form->end('Submit ISBN');
?>
