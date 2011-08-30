<?php

class UsersController extends AppController
{
	var $name = 'Users';

	function index()
	{
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
				$this->redirect('../books');
			else
				$this->redirect('login?error='.
								urlencode('Error: Please try again'));
		}
	}
}

?>
