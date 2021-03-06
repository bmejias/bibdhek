<?php

/**
 * Author: Boriss Mejias <tchorix@gmail.com>
 */

class BooksController extends AppController
{
    var $name = 'Books';

    function index()
    {
        $books = $this->Book->find('all');
        $this->set('books', $books);
    }

    function add()
    {
    }

    function add_by_isbn() { }

    /*
     * @pre: request->data comes ready to be save as a book in the database
     */
    function do_add()
    {
        // Just save the book as the data comes
        if ($this->Book->save($this->request->data))
        {
            // If saving the book works, add the copies
            $book_id = $this->Book->id;
            $copies = 1;
            if ($this->request->data['Book']['copies'] > 1)
                $copies = $this->request->data['Book']['copies'];
            Controller::loadModel('Copy');
            for ($i = 1; $i <= $copies; $i++)
            {
                $new_copy = array('Copy' =>
                                      array('book_id'   => $book_id,
                                            'status'    => Copy::$AVAILABLE,
                                            'code'      => $i));
                $this->Copy->create();
                $result = $this->Copy->save($new_copy);
                $this->debug("Saving copy returned ".print_r($result, true));
            }
            $this->Session->setFlash('The book has been saved.');
            $this->redirect(array('action' => 'index'));
        }
        $this->redirect(array('action' => 'index'));
    }

    function edit()
    {
        $book_id = $_GET['book_id'];
        $book = $this->Book->findById($book_id);
        $this->set('book', $book['Book']);
        $this->set('copies', $this->getRichCopies($book_id));
    }

    function extend_it($data)
    {
        Controller::loadModel('User');
        Controller::loadModel('Loan');
        Controller::loadModel('Rule');

        $this->debug("The POST: ".print_r($_POST, true));
        $this->debug("The GET: ".print_r($_GET, true));
        if (!isset($data))
            $data = (isset($_POST['data'])) ? $_POST['data']['Book'] : $_GET;
        $this->debug("The new DATA: ".print_r($data, true));
        $this->debug("The data is ".print_r($this->request->data, true));
        $user = $this->User->findById($data['user_id']);
        $student = $user['User']['first_name']." ".$user['User']['last_name'];

        $today  = date('Y-m-d', time());
        $loan   = $this->Loan->findById($data['loan_id']);
        $raw_fine = $this->Rule->get_fine($loan['Loan']['date_return'], $today);

        $back_to = '../books/view?book_id='.$data['book_id'];
        if (isset($data['back_to']) && $data['back_to'] == 'user')
            $back_to = '../users/view?user_id='.$data['user_id'];

        $this->set('student', $student);
        $this->set('fine', $raw_fine - $loan['Loan']['paid']);
        $this->set('loan', $loan['Loan']);
        $this->set('today', $today);
        $this->set('back_to', $back_to);
        $this->set('user_id', $data['user_id']);
        $this->set('loan_id', $data['loan_id']);
        $this->setBookAndCopy($data);
    }


    /*
     * @pre: request->data comes ready to be save as a book in the database
     */
    function do_edit()
    {
        // TODO: add a verification to $update_data
        $this->debug("This is the request data: ".print_r($this->request->data, true));
        $update_data = $this->request->data['Book'];
        $book_id = $update_data['id'];
        $this->debug("Going to update book with id ".$book_id);
        $this->debug("And data ".print_r($update_data, true));
        $update_book = $this->Book->update($book_id, $update_data);
        $this->debug("update_book is ".print_r($update_book, true));

        // Feedback of the action
        $msg = "";
        if ($update_book)
            $msg = "The book has been correctly updated.";
        else
            $msg = "There was a problem updating the book.";
        $this->Session->setFlash($msg);
        $this->redirect(array('action' => 'index'));
    }

    function lend()
    {
        Controller::loadModel('User');
        Controller::loadModel('Rule');

        $all_users  = $this->User->find('all');
        $users      = array();
        foreach ($all_users as $user)
            $users[$user['User']['id']] = $user['User']['first_name']." ".
                                          $user['User']['last_name'];
        $now = time();
        $date_return = $this->Rule->get_date_return($now);
        $deposit = $this->Rule->get_deposit();

        $this->set('date_out', date('Y-m-d', $now));
        $this->set('date_return', date('Y-m-d', $date_return));
        $this->set('deposit', $deposit);
        $this->set('users', $users);
        $this->setBookAndCopy($this->request->data['Book']);
    }

    function edit_book_copy()
    {
        $this->debug("The full post ".print_r($_POST['data'], true));
        $data = (isset($_POST['data'])) ? $_POST['data']['Book'] : $_GET;
        $this->debug(print_r($data, true));
        if (isset($data['return']))
        {
            $this->return_it($data);
            return $this->render('return_it');
        }
        else
        {
            $this->extend_it($data);
            return $this->render('extend_it');
        }
    }

    function return_it($data)
    {
        Controller::loadModel('User');
        Controller::loadModel('Loan');
        Controller::loadModel('Rule');

        $this->debug("Getting the following data from arguments: ");
        $this->debug(print_r($data, true));
        $this->debug("The POST: ".print_r($_POST, true));
        $this->debug("The GET: ".print_r($_GET, true));
        if (!isset($data))
            $data = (isset($_POST['data'])) ? $_POST['data']['Book'] : $_GET;
        $this->debug("The new DATA: ".print_r($data, true));
        $this->debug("The data is ".print_r($this->request->data, true));
        $user = $this->User->findById($data['user_id']);
        $student = $user['User']['first_name']." ".$user['User']['last_name'];

        $today  = date('Y-m-d', time());
        $loan   = $this->Loan->findById($data['loan_id']);
        $raw_fine = $this->Rule->get_fine($loan['Loan']['date_return'], $today);

        $back_to = '../books/view?book_id='.$data['book_id'];
        if (isset($data['back_to']) && $data['back_to'] == 'user')
            $back_to = '../users/view?user_id='.$data['user_id'];

        $this->set('student', $student);
        $this->set('fine', $raw_fine - $loan['Loan']['paid']);
        $this->set('loan', $loan['Loan']);
        $this->set('today', $today);
        $this->set('back_to', $back_to);
        $this->set('user_id', $data['user_id']);
        $this->set('loan_id', $data['loan_id']);
        $this->setBookAndCopy($data);
    }

    function use_isbn()
    {
        /* Connect to goggle books to retrieve book's information based on it's
        isbn number, and then go to 'books/add' to confirm the addition (and
        eventually edit the data). If it doesn't find it, it prompts a 'not
        found' message.
        */
    }

    function view()
    {
        $book_id = $_GET['book_id'];
        $book = $this->Book->findById($book_id);
        $this->debug("Searching book $book_id got ".print_r($book, true));
        $this->set('book', $book['Book']);
        $this->set('copies', $this->getRichCopies($book_id));
    }

    /*-----------------------------------------------------------------------
     * Functions to help the controllers
     *-----------------------------------------------------------------------
     */

    private function getRichCopies($book_id)
    {
        Controller::loadModel('Copy');
        $copies = $this->Copy->findAllByBook_id($book_id);
        $this->debug("Searching copies for book $book_id got ".print_r($copies, true));
        // rich_copies are a set of copies with a detailled information
        $rich_copies = array();
        $i = 0;
        foreach ($copies as $copy)
        {
            $copy = $copy['Copy'];
            if ($copy['status'] == Copy::$LENT)
            {
                Controller::loadModel('Loan');
                Controller::loadModel('User');
                $query = array('Loan.copy_id' => $copy['id'],
                               'Loan.status' => Copy::$LENT);
                $loan = $this->Loan->find('first',
                                          array('conditions' => $query));
                $user = $this->User->findById($loan['Loan']['user_id']);
                $copy['loan_id'] = $loan['Loan']['id'];
                $copy['user_id'] = $loan['Loan']['user_id'];
                $copy['student'] = $user['User']['first_name']." ".
                                   $user['User']['last_name'];
                $copy['fine'] = $loan['Loan']['fine'];
                $copy['date_return'] = $loan['Loan']['date_return'];
            }
            else
            {
                /* status is available or not_to_lend */
                $copy['loan_id']    = '-';
                $copy['user_id']    = '-';
                $copy['student']    = '-';
                $copy['fine']       = '-';
                $copy['date_return'] = '-';
            }
            $this->debug("Adding the following copy ".print_r($copy, true));
            $rich_copies[$i] = $copy;
            $i++;
        }
        $this->debug("These are the rich copies ".print_r($rich_copies, true));
        return $rich_copies;
    }

    private function setBookAndCopy($data)
    {
        Controller::loadModel('Copy');
        $book   = $this->Book->findById($data['book_id']);
        $copy   = $this->Copy->findById($data['copy_id']);
        $this->set('book', $book['Book']);
        $this->set('copy', $copy['Copy']);
    }

    public function setAvailableCopies($books)
    {
        return $books;
    }

}

?>

