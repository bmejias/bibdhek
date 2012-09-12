<?php

class UsersController extends AppController
{
    var $name = 'Users';

    function index()
    {
        $this->set('mode', isset($_GET['mode']) ? $_GET['mode'] : 'simple');
        $this->set('users', $this->User->find('all'));
    }

    function add()
    {
        $this->set('mode', isset($_GET['mode']) ? $_GET['mode'] : 'simple');
        if (!empty($this->request->data))
        {
            if ($this->request->data['User']['mode'] == 'simple')
            {
                /* complete data in case of simple mode */
                $this->User->create();
                $this->request->data['User']['username'] = uniqid(); 
                $this->request->data['User']['password'] = "nopassword";
                $this->request->data['User']['confirm'] = "nopassword";
            }
            if ($this->request->data['User']['password']
                == $this->request->data['User']['confirm'])
            {
                if ($this->User->save($this->request->data))
                {
                    $this->Session->setFlash('The user has been created.');
                    $this->redirect(array('action' => 'index'));
                }
            }
            else
                $this->Session->setFlash('Passwords do not match - Try again');
        }
    }

    function check_login()
    {
        $this->set('result', 'error');
        if (!empty($this->request->data))
        {
            $data = $this->request->data['login'];
            $user = $this->User->findByUsername($data['username']);
            if ($user != null && $user['User']['password'] == $data['password'])
                $this->redirect('../pages/index');
            else
                $this->redirect('login?error='.
                                urlencode('Error: Please try again'));
        }
    }

    function login()
    {
        $msg = "Gelieve in te loggen";
        if (isset($_GET['error']))
            $msg = $_GET['error'];
        $this->set('msg', $msg);
    }

    function status()
    {
    }

    function view()
    {
        $user_id = $_GET['user_id'];
        $user = $this->User->findById($user_id);
        $this->set('user', $user['User']);

        Controller::loadModel('Loan');
        Controller::loadModel('Copy');
        $this->debug("This is the user id: ".$user_id);
        $user_loans = $this->Loan->get_from_user($user_id);
        $this->debug("LOANS - found this:\n".print_r($user_loans,true));
        $total_fine     = 0;
        $total_deposit  = 0;
        for ($i = 0; $i < count($user_loans); $i++)
        {
            $book_info = $this->Copy->get_book_info($user_loans[$i]['copy_id']);
            $user_loans[$i]['book_id']      = $book_info['id'];
            $user_loans[$i]['book_title']   = $book_info['title'];
            $total_fine += $user_loans[$i]['fine'];
            $total_deposit += $user_loans[$i]['deposit'];
        }
        $this->debug("LOANS - adding book title:\n".print_r($user_loans,true));
        $this->set('loans', $user_loans);
        $this->set('total_fine', $total_fine);
        $this->set('total_deposit', $total_deposit);
    }

    function bulk_return()
    {
        $data = isset($_POST['data']) ? $_POST['data']['User'] : array();
        $this->debug("This is the data:\n".print_r($data, true));
        // foreach ($data['']
        $this->redirect('/'); 
    }
}

?>
