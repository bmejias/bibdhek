<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');
App::uses('CartController', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    function beforeFilter()
    {
        /* Checking for the Shopping Cart - Not really shopping by lending */
        $cart_msg = "Empty Shopping cart";
        $cart = $this->Session->read('cart');
        if ($cart != null)
        {
            $cart_msg = $cart['user']." is taking ".sizeof($cart['copies'])
                        ." books";
        }
        CartController::set_message($this->Session, $cart_msg);
        // $this->set_message($cart_msg);
        // $this->Session->write('cart_msg', $cart_msg);
    }

    function debug($message)
    {
        $this->log($message, LOG_DEBUG);
    }
}
