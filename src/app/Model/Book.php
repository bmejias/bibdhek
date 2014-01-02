<?php

class Book extends AppModel
{
    var $name = 'Book';

    static $LANGS =  array( 'nl' => 'Nederlands',
                            'en' => 'English',
                            'fr' => 'Français',
                            'es' => 'Español',
                            'de' => 'Deutsch');
}

?>
