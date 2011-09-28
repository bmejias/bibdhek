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
			Controller::loadModel('Material');
			for ($i = 1; $i <= $copies; $i++)
			{
				$new_material = array('Material' =>
									  array('book_id'	=> $book_id,
											'status'	=> 'available',
											'code'		=> $i));
				$this->Material->create();
				$result = $this->Material->save($new_material);
				$this->debug("Saving material returned ".print_r($result, true));
			}
			$this->Session->setFlash('The book has been saved.');
			$this->redirect(array('action' => 'index'));
		}
		$this->redirect(array('action' => 'index'));
	}

	/* pre: $this->data['Book']['user'] should be a valid user */
	/* pre: $this->data['Book']['copy'] should be a valid material */
	function do_lend()
	{
		$this->helpers[] = 'Time';
		$this->debug("THE DATA----------------------");
		$this->debug(print_r($this->data, true));
		$this->debug("THE BOOK----------------------");
		$this->debug(print_r($this->data['Book'], true));
		$input = $this->data['Book'];
		if (isset($input['lend']))
		{
			/* perform the loan */
			Controller::loadModel('Loan');
			Controller::loadModel('Material');

			$copy_id	= $input['copy'];
			$user_id	= $input['user'];

			$loan = array('Loan'=>
							array('material_id'	=> $copy_id,
								  'user_id'		=> $user_id,
								  'date_out'	=> $input['date_out'],
								  'date_return'	=> $input['date_return'],
								  'status'		=> 'lent'));
			if ($input['cd'])
			{
				$loan['Loan']['cd']	= true;
				$loan['Loan']['deposit'] = $input['deposit'];
			}

			$this->Loan->create();
			if ($this->Loan->save($loan))
			{
				$this->Material->id = $copy_id;
				$this->Material->saveField('status', 'lent');
				$this->Session->setFlash('The book has been lent.');
			}
		}
		/* if the action is to cancel the loan, do nothing */
		/* elseif ($input['do'] == 'cancel') */
		/* redirect to book's view in any case */
		$this->redirect('view?book_id='.$input['book']);
	}

	function do_return()
	{
		Controller::loadModel('Material');
		Controller::loadModel('User');
		Controller::loadModel('Loan');

		$copy_id = $this->data['Book']['copy'];
		$this->Material->id = $copy_id;
		$this->Material->saveField('status', 'available');

		$query = array('Loan.material_id' => $copy_id, 'Loan.status' => 'lent');
		$loan = $this->Loan->find('first', array('conditions' => $query)); 
		$this->Loan->id = $loan['Loan']['id'];
		$this->Loan->set(array('date_in'	=> date('Y-m-d'),
							   'status'		=> 'returned',
							   'fine'		=> 0.0));
		$this->Loan->save();

		$this->redirect('view?book_id='.$this->data['Book']['book']);	
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

		$this->debug("The data is ".print_r($this->data, true));
		$user = $this->User->findById($this->data['Book']['user_id']);
		$student = $user['User']['first_name']." ".$user['User']['last_name'];

		/* it is assumed that loan contains already the right deposit, fine,
		 * and already paid money.
		 */
		$loan		= $this->Loan->findById($this->data['Book']['loan_id']);
		$fine		= $loan['Loan']['fine'] - $loan['Loan']['paid'];

		$this->set('student', $student);
		$this->set('fine', $fine);
		$this->set('loan', $loan['Loan']);
		$this->set('today', date('Y-m-d', time()));
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
		Controller::loadModel('Material');
		$copies = $this->Material->findAllByBook_id($book_id);
		$this->debug("Searching copies for book $book_id got ".print_r($copies, true));
		$rich_copies = array();
		$i = 0;
		foreach ($copies as $material)
		{
			$copy = $material['Material'];
			if ($copy['status'] == 'lent')
			{
				Controller::loadModel('Loan');
				Controller::loadModel('User');
				$query = array('Loan.material_id' => $copy['id'],
							   'Loan.status' => 'lent');
				$loan = $this->Loan->find('first',
										  array('conditions' => $query));
				$user = $this->User->findById($loan['Loan']['user_id']);
				$copy['loan_id'] = $loan['Loan']['id'];
				$copy['user_id'] = $loan['Loan']['user_id'];
				$copy['student'] = $user['User']['first_name']." ".
								   $user['User']['last_name'];
				$copy['fine'] = $loan['Loan']['fine'];
			}
			else
			{
				/* status is available or not_to_lend */
				$copy['loan_id']	= '-';
				$copy['user_id']	= '-';
				$copy['student']	= '-';
				$copy['fine']		= '-';
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
		Controller::loadModel('Material');
		$book	= $this->Book->findById($data['book']);
		$copy	= $this->Material->findById($data['copy']);
		$this->set('book', $book['Book']);
		$this->set('copy', $copy['Material']);

	}

}

?>

