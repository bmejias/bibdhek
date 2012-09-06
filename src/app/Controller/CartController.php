<?php

/**
 * Author: Boriss Mejias <tchorix@gmail.com>
 */

class CartController extends AppController
{
    public $name        = "Cart";
    public $uses        = array(); // does not use a model?
    public $useTable    = false;

    public function start()
    {
        $cart = $this->Session->read('cart');
        if ($cart != null)
        {
            $this->Session->setFlash("ERROR: There is already a shopping cart");
            $this->redirect('/');
        }
        $user_id = $_GET['user_id'];
        Controller::loadModel('User');
        $user = $this->User->findById($user_id);
        $user_name = $user['User']['first_name']." ".$user['User']['last_name'];
        $cart = array('user_id' => $user_id,
                      'user'    => $user_name,
                      'copies'   => array());
        $this->Session->write('cart', $cart);

        $this->redirect('/');
    }

    public function destroy()
    {
        $this->Session->write('cart', null);
        $this->redirect('/');
    }

    public function add()
    {
        $cart = $this->getCart();
        $copy_id = $_GET['copy_id'];
        Controller::loadModel('Copy');
        Controller::loadModel('Book');
        $copy = $this->Copy->findById($copy_id);
        $book = $this->Book->findById($copy['Copy']['book_id']);
        $author = $book['Book']['author'];
        $title = $book['Book']['title'];
        $new_copy = sizeof($cart['copies']);
        $cart['copies'][$new_copy] = array('copy_id' => $copy_id,
                                           'book_id' => $book_id,
                                           'author' => $author,
                                           'title' => $title);
        $this->Session->write('cart', $cart);
        $this->redirect('/');
    }

    public function view()
    {
        $this->set('mini_display', false);
    }

    public function commit()
    {
        $cart = $this->getCart();
        Controller::loadModel('Rule');
        Controller::loadModel('Loan');
        Controller::loadModel('Copy');
        $now = time();
        $date_return = $this->Rule->get_date_return($now);
        $now = date('Y-m-d', $now);
        $date_return = date('Y-m-d', $date_return);
        $deposit = $this->Rule->get_deposit(); // TODO: Add CDs to the Cart
        $user_id = $cart['user_id'];
        foreach ($cart['copies'] as $copy)
        {
            $copy_id = $copy['copy_id'];
            $add_loan = $this->Loan->add_loan($copy_id, $user_id, $now,
                                              $date_return, false, $deposit);
            /* TODO: Modifying Loan and Copy should done in a transaction */
            if ($add_loan)
            {
                $this->Copy->setToLent($copy_id);
            }
        }
        $this->Session->setFlash('Date to return:'.$date_return);
        $this->redirect('/');
    }

    private function getCart()
    {
        $cart = $this->Session->read('cart');
        if ($cart == null)
        {
            $this->Session->setFlash("ERROR: There is no shopping cart");
            $this->redirect('/');
        }
        return $cart;
    }
}

?>
