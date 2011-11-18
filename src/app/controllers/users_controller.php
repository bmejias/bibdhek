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
		if (!empty($this->data))
		{
			if ($this->data['User']['mode'] == 'simple')
			{
				/* complete data in case of simple mode */
				$this->User->create();
				$this->data['User']['username'] = uniqid(); 
				$this->data['User']['password'] = "nopassword";
				$this->data['User']['confirm'] = "nopassword";
			}
			if ($this->data['User']['password'] == $this->data['User']['confirm'])
			{
				if ($this->User->save($this->data))
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
		if (!empty($this->data))
		{
			$data = $this->data['login'];
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
		for ($i = 0; $i < count($user_loans); $i++)
		{
			$book_info = $this->Copy->get_book_info($user_loans[$i]['copy_id']);
			$user_loans[$i]['book_id']		= $book_info['id'];
			$user_loans[$i]['book_title']	= $book_info['title'];
		}
		$this->debug("LOANS - adding book title:\n".print_r($user_loans,true));
		$this->set('loans', $user_loans);
	}
}

?>
