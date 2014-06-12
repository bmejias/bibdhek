<?php

/**
 * Author Boriss Mejias <tchorix@gmail.com>
 */

class Copy extends AppModel
{
    var $name = 'Copy';

    static $AVAILABLE   = 'available';
    static $LENT        = 'lent';

    /**
     * Find the title and id of the book associated with the copy_id
     *
     * @param serial $copy_id 
     * @return array with book's title and book's id.
     */
    function get_book_info($copy_id)
    {
        $query = "SELECT books.id, books.title FROM books WHERE books.id = ";
        $query.= "(SELECT book_id FROM copies WHERE id = ".$copy_id.")";
        $result = $this->query($query);
        //echo "This is what I got from the query\n".print_r($result, true);
        $to_return = array('id' => 0, 'title' => "ERROR - book not found");
        if (count($result) > 0)
        {
            $to_return['id']    = $result[0][0]['id'];
            $to_return['title'] = $result[0][0]['title'];
        }
        return $to_return;
    }

    function setToLent($copy_id)
    {
        $this->id = $copy_id;
        $this->saveField('status', Copy::$LENT);
    }

    function setToAvailable($copy_id)
    {
        $this->id = $copy_id;
        $this->saveField('status', Copy::$AVAILABLE);
    }
}

?>
