<?php
/**
 * Author: Boriss Mejias <tchorix@gmail.com>
 */
?>

<h2>Edit Book</h2>

<?php

echo $this->Form->create('Book', array('type' => 'post', 'action' => 'do_add'));
echo $this->Form->input('title', array('type' => 'text',
                                       'size' => '30',
                                       'value' => $book['title']));
echo $this->Form->input('author', array('type' => 'text',
                                        'size' => '30',
                                        'value' => $book['author']));
// TODO: I need to confirm the use of the variable $book['cd'] on this form
echo $this->Form->input('cd', array('type' => 'checkbox',
                                    'label' => 'CD',
                                    'value' => $book['cd']));
echo $this->Form->input('lang', array('label' => 'Language',
                                      'options' => $langs,
                                      'default' => $langs[$book['lang']]));
echo $this->Form->input('level', array('type' => 'text',
                                       'size' => '7',
                                       'value' => $book['level']));
echo $this->Form->input('collection', array('type' => 'text',
                                            'size' => '30',
                                            'value' => $book['collection']));
echo $this->Form->input('isbn', array('type' => 'text',
                                      'size' => '13',
                                      'value' => $book['isbn']));
// TODO: Editing the number of copies is not that simple in case that some of 
// the copies are currently lent. Keeping this option fo later
/*
echo $this->Form->input('copies', array('type' => 'text',
                                        'size' => '3',
                                        'label' => 'Number of Copies',
                                        'value' => sizeof($copies)));
 */
echo $this->Form->input('publisher', array('type' => 'text',
                                           'size' => '30',
                                           'value' => $book['publisher']));
echo $this->Form->input('date', array('type' => 'text',
                                      'size' => '16',
                                      'label' => 'Publication Date',
                                      'value' => $book['date']));
echo $this->Form->input('acquired', array('type' => 'text',
                                          'size' => '16',
                                          'label' => 'Acquisition Date',
                                          'value' => $book['acquired']));
echo $this->Form->input('obs', array('type' => 'text',
                                     'size' => '30',
                                     'label' => 'Observation',
                                     'value' => $book['obs']));
echo $this->Form->end('Edit Book');

?>
