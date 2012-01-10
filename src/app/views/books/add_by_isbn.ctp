<!-- File: /app/views/books/add_by_isbn.ctp -->

<h2>Add Book by ISBN</h2>

<?php
$form = $this->Form;

echo $form->create('Book', array('type' => 'post', 'action' => 'use_isbn'));
echo $form->input('ISBN', array('type' => 'text', 'size' => '13'));
echo $form->end('Submit ISBN');
?>
