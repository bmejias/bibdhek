<?php

class UsersController extends AppController
{
	var $name = 'Users';

	function index()
	{
		$this->set('mode', isset($_GET['mode']) ? $_GET['mode'] : 'simple');
		$this->set('users', $this->User->find('all'));
	}

	function login()
	{
		$msg = "Gelieve in te loggen";
		if (isset($_GET['error']))
			$msg = $_GET['error'];
		$this->set('msg', $msg);
		;
	}

	function check_login()
	{
		$this->set('result', 'error');
		if (!empty($this->data))
		{
			$data = $this->data['login'];
			$user = $this->User->findByUsername($data['username']);
			if ($user != null && $user['User']['password'] == $data['password'])
				$this->redirect('../pages/admin');
			else
				$this->redirect('login?error='.
								urlencode('Error: Please try again'));
		}
	}

	function add()
	{
		if (!empty($this->data))
		{
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
}

?>
