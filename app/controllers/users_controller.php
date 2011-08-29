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
		if (!empty($this->data))
		{
			$data = $this->data['login'];
			$user = $this->User->findByUsername($data['username']);
			if ($user == null)
			{
				$this->log("CHECK_LOGIN: Didn't found user ".$this->data['login']['username']);
				$this->set('result', 'error');
			}
			else
			{
				if ($user['User']['password'] == $data['password'])
					$this->set('result', 'logon');
				else
					$this->set('result', 'error');
			}
		}
	}
}

?>
