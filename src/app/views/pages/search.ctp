<!-- File: /app/views/users/search.ctp -->

<?php

echo $this->Form->create('search', array('action' => '../search/do_search'));
echo $this->Form->input('query', array('text' => 'Search'));
echo $this->Form->submit('Search', array('name'=>'search'));

$options = array('all'      => 'all',
                 'title'    => 'title',
                 'author'   => 'author',
                 'user'     => 'user');
$attribs = array('value'    => 'all',
                 'legend'   => false);
echo $this->Form->radio('options', $options, $attribs);
echo $this->Form->end();
?>
