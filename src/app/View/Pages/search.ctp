<!-- File: /app/views/users/search.ctp -->

<?php

echo $this->Form->create('search', array('action' => '../search/do_search'));
echo $this->Form->input('query', array('text' => 'Search'));
echo $this->Form->submit('Search', array('name'=>'search', 'id'=>'main_search'));

$options = array('all'      => 'all',
                 'title'    => 'title',
                 'author'   => 'author',
                 'user'     => 'user');
$attribs = array('value'    => 'all',
                 'legend'   => false);
?>
<div class="search_options">
<?php
    echo $this->Form->radio('options', $options, $attribs);
?>
</div>
<?php
echo $this->Form->end();
?>
