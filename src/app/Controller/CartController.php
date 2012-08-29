<?php

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
        $input = $this->request->data['Cart'];
        if (isset($input['destroy']))
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
