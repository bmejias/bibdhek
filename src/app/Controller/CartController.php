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
}

?>
