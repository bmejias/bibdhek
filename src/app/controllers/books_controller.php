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

	function do_lend()
	{
		if ($this->data['Book']['do'] == 'lend')
		{
			/* perform the loan */
			Controller::loadModel('Loan');
			Controller::loadModel('Material');

			$copy_id 	= $this->data['Book']['copy'];
			$user_id	= $this->data['Book']['user'];
			$loan = array('Loan'=>
							array('material_id'	=> $copy_id,
								  'user_id'		=> $user_id,
								  'date_out'	=> date('Y-m-d'),
								  'status'		=> 'lent',
								  'money'		=> 10));
			$this->Loan->create();
			if ($this->Loan->save($loan))
			{
				$this->Material->id = $copy_id;
				$this->Material->saveField('status', 'lent');
				$this->Session->setFlash('The book has been lent.');
			}
		}
		/* if the action is to cancel the loan, do nothing */
		/* elseif ($this->data['Book']['do'] == 'cancel') */
		/* redirect to book's view in any case */
		$this->redirect('view?book_id='.$this->data['Book']['book']);
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
							   'money'		=> 0));
		$this->Loan->save();

		$this->redirect('view?book_id='.$this->data['Book']['book']);	
	}

	function lend()
	{
		Controller::loadModel('Material');
		Controller::loadModel('User');

		$book	= $this->Book->findById($this->data['Book']['book']);
		$copy	= $this->Material->findById($this->data['Book']['copy']);
		$this->set('book', $book['Book']);
		$this->set('copy', $copy['Material']);

		$all_users	= $this->User->find('all');
		$users		= array();
		foreach ($all_users as $user)
			$users[$user['User']['id']] = $user['User']['first_name']." ".
										  $user['User']['last_name'];
		$this->set('users', $users);
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
				$copy['student'] = $user['User']['first_name']." ".
								   $user['User']['last_name'];
				$copy['fine'] = $loan['Loan']['money'];
			}
			else
			{
				/* status is available or not_to_lend */
				$copy['student'] = '-';
				$copy['fine'] = '0.00';
			}
			$this->debug("Adding the following copy ".print_r($copy, true));
			$rich_copies[$i] = $copy;
			$i++;
		}
		$this->debug("These are the rich copies ".print_r($rich_copies, true));
		$this->set('copies', $rich_copies);
	}

}

?>

