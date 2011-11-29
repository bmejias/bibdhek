<?php

class SearchController extends AppController
{
    var $name       = 'Search';
    var $uses       = array();
    var $useTable   = false;

    function index()
    {
        $this->redirect('../pages/search');
    }

    function do_search()
    {
        
        Controller::loadModel('Book');
        Controller::loadModel('User');
        $this->debug("Getting the form ".print_r($this->data, true));
        $the_query      = $this->data['search']['query'];
        $option         = $this->data['search']['options'];
        /* define search fields for books depending on optinos. Using record 
         * instead of if statements.
         */
        $book_option_fields = array('title' => array('Book.title'),
                                    'author'=> array('Book.author'),
                                    'all'   => array('Book.title',
                                                     'Book.author',
                                                     'Book.level'));
        $book_fields    = $book_option_fields[$option];
        $user_fields    = array('User.username',
                                'User.first_name',
                                'User.last_name');
        /* perform search depending on options */
        $books = array();
        $users = array();
        if ($option == 'all' || $option == 'title' || $option == 'author')
            $books  = $this->searchIn($this->Book, $book_fields, $the_query);
        if ($option == 'all' || $option == 'user')
            $users  = $this->searchIn($this->User, $user_fields, $the_query);
        $this->set('books', $books);
        $this->set('users', $users);
        $this->render('../search/results');
    }

    function results()
    {
        $this->debug("The controller of the results has been called!");
    }

    /*-----------------------------------------------------------------------
     * Functions to help the controllers
     *-----------------------------------------------------------------------
     */
    private function searchIn($model, $fields, $full_query)
    {
        /* Implementation based on "Simple search in CakePHP using find 
         * conditions", posted by saravana at CakePHP's bakery.
         */
        $conditions     = array();
        $or_conditions  = array();
        $final_conditions = array();

        $full_query = strtolower($full_query);
        $queries    = explode(" ", $full_query);
        foreach ($fields as $f)
        {
            array_push($conditions, array("LOWER($f) Like" => "%$full_query%"));
            for ($i = 0; $i < count($queries); $i++)
            {
                if ($queries[$i] != "")
                    array_push($conditions,
                               array("LOWER($f) Like" => "%".$queries[$i]."%"));
            }

            array_push($or_conditions, array('OR' => $conditions));
            $conditions = array();
        }
        $final_conditions = array('OR' => $or_conditions);
        $results = $model->find('all',
                                array('conditions' => $final_conditions));
        return $results;
    }

}

?>

