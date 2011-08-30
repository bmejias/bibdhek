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
				$this->set('result', 'logon');
		}
	}
}

?>
