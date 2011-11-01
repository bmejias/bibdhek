<?php

class BooksController extends AppController
{
	var $name = 'Books';

	function index()
	{
		$this->set('books', $this->Book->find('all'));
	}

	function add()
	{
	}

	function do_add()
	{
		if ($this->Book->save($this->data))
		{
			$book_id = $this->Book->id;
			$copies = 1;
			if ($this->data['Book']['copies'] > 1)
				$copies = $this->data['Book']['copies'];
			Controller::loadModel('Copy');
			for ($i = 1; $i <= $copies; $i++)
			{
				$new_copy = array('Copy' =>
									  array('book_id'	=> $book_id,
											'status'	=> 'available',
											'code'		=> $i));
				$this->Copy->create();
				$result = $this->Copy->save($new_copy);
				$this->debug("Saving copy returned ".print_r($result, true));
			}
			$this->Session->setFlash('The book has been saved.');
			$this->redirect(array('action' => 'index'));
		}
		$this->redirect(array('action' => 'index'));
	}

	function lend()
	{
		Controller::loadModel('User');
		Controller::loadModel('Rule');

		$all_users	= $this->User->find('all');
		$users		= array();
		foreach ($all_users as $user)
			$users[$user['User']['id']] = $user['User']['first_name']." ".
										  $user['User']['last_name'];
		$now = time();
		$date_return = $this->Rule->get_date_return($now);
		$deposit = $this->Rule->get_deposit();

		$this->set('date_out', date('Y-m-d', $now));
		$this->set('date_return', date('Y-m-d', $date_return));
		$this->set('deposit', $deposit);
		$this->set('users', $users);
		$this->setBookAndCopy($this->data['Book']);
	}

	function return_it()
	{
		Controller::loadModel('User');
		Controller::loadModel('Loan');
		Controller::loadModel('Rule');

		$this->debug("The data is ".print_r($this->data, true));
		$user = $this->User->findById($this->data['Book']['user_id']);
		$student = $user['User']['first_name']." ".$user['User']['last_name'];

		$today	= date('Y-m-d', time());
		$loan	= $this->Loan->findById($this->data['Book']['loan_id']);
		$raw_fine = $this->Rule->get_fine($loan['Loan']['date_return'], $today);

		$this->set('student', $student);
		$this->set('fine', $raw_fine - $loan['Loan']['paid']);
		$this->set('loan', $loan['Loan']);
		$this->set('today', $today);
		$this->set('user_id', $this->data['Book']['user_id']);
		$this->set('loan_id', $this->data['Book']['loan_id']);
		$this->setBookAndCopy($this->data['Book']);
	}

	function view()
	{
		$book_id = $_GET['book_id'];
		$book = $this->Book->findById($book_id); 
		$this->debug("Searching book $book_id got ".print_r($book, true));
		$this->set('book', $book['Book']);
		Controller::loadModel('Copy');
		$copies = $this->Copy->findAllByBook_id($book_id);
		$this->debug("Searching copies for book $book_id got ".print_r($copies, true));
		$rich_copies = array();
		$i = 0;
		foreach ($copies as $copy)
		{
			$copy = $copy['Copy'];
			if ($copy['status'] == 'lent')
			{
				Controller::loadModel('Loan');
				Controller::loadModel('User');
				$query = array('Loan.copy_id' => $copy['id'],
							   'Loan.status' => 'lent');
				$loan = $this->Loan->find('first',
										  array('conditions' => $query));
				$user = $this->User->findById($loan['Loan']['user_id']);
				$copy['loan_id'] = $loan['Loan']['id'];
				$copy['user_id'] = $loan['Loan']['user_id'];
				$copy['student'] = $user['User']['first_name']." ".
								   $user['User']['last_name'];
				$copy['fine'] = $loan['Loan']['fine'];
				$copy['date_return'] = $loan['Loan']['date_return'];
			}
			else
			{
				/* status is available or not_to_lend */
				$copy['loan_id']	= '-';
				$copy['user_id']	= '-';
				$copy['student']	= '-';
				$copy['fine']		= '-';
				$copy['date_return'] = '-';
			}
			$this->debug("Adding the following copy ".print_r($copy, true));
			$rich_copies[$i] = $copy;
			$i++;
		}
		$this->debug("These are the rich copies ".print_r($rich_copies, true));
		$this->set('copies', $rich_copies);
	}

	/*-----------------------------------------------------------------------
	 * Functions to help the controllers
	 *-----------------------------------------------------------------------
	 */
	private function setBookAndCopy($data)
	{
		Controller::loadModel('Copy');
		$book	= $this->Book->findById($data['book']);
		$copy	= $this->Copy->findById($data['copy']);
		$this->set('book', $book['Book']);
		$this->set('copy', $copy['Copy']);

	}

}

?>

