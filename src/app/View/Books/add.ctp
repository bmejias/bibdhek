<?php
/**
 * Author: Boriss Mejias <tchorix@gmail.com>
 */
?>

<h2>Add Book</h2>

<?php

echo $this->Form->create('Book', array('type' => 'post', 'action' => 'do_add'));
echo $this->Form->input('title', array('type' => 'text', 'size' => '30'));
echo $this->Form->input('author', array('type' => 'text', 'size' => '30'));
echo $this->Form->input('cd', array('type' => 'checkbox', 'label' => 'CD'));
echo $this->Form->input('lang', array('label' => 'Language',
                                      'options' => BOOK::$LANGS));
echo $this->Form->input('level', array('type' => 'text', 'size' => '7'));
echo $this->Form->input('collection', array('type' => 'text', 'size' => '30'));
echo $this->Form->input('isbn', array('type' => 'text', 'size' => '13'));
echo $this->Form->input('copies', array('type' => 'text',
                                        'size' => '3',
                                        'label' => 'Number of Copies',
                                        'default' => '1'));
echo $this->Form->input('publisher', array('type' => 'text', 'size' => '30'));
echo $this->Form->input('date', array('type' => 'text',
                                      'size' => '16',
                                      'label' => 'Publication Date'));
echo $this->Form->input('acquired', array('type' => 'text',
                                          'size' => '16',
                                          'label' => 'Acquisition Date'));
echo $this->Form->input('obs', array('type' => 'text',
                                     'size' => '30',
                                     'label' => 'Observation'));
echo $this->Form->end('Submit Book');

?>
