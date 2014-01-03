<?php

class Book extends AppModel
{
    var $name = 'Book';

    static $LANGS =  array( 'nl' => 'Nederlands',
                            'en' => 'English',
                            'fr' => 'Français',
                            'es' => 'Español',
                            'de' => 'Deutsch');

    function update($book_id, $update_data)
    {
        $this->id = $book_id;
        $this->set($update_data);
        return $this->save();
    }

}

?>
