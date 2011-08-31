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
		if (!empty($this->data))
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
					$new_material = array('Material'=>
										  array('book_id'=>$book_id,
												'status'=>'available',
												'code'=>$i));
					$this->Material->create();
					$result = $this->Material->save($new_material);
					$this->log("Saving material returned ".print_r($result,
																   true));
				}
				$this->Session->setFlash('The book has been saved.');
				$this->redirect(array('action' => 'index'));
			}
			$this->redirect(array('action' => 'index'));
		}
	}

	function view()
	{
		$book_id = $_GET['book_id'];
		$book = $this->Book->findById($book_id); 
		$this->log("Searching book $book_id got ".print_r($book, true));
		$this->set('book', $book['Book']);
		Controller::loadModel('Material');
		$copies = $this->Material->findAllByBook_id($book_id);
		$this->log("Searching copies for book $book_id got ".print_r($copies, true));
		$rich_copies = array();
		$i = 0;
		foreach ($copies as $material)
		{
			$copy = $material['Material'];
			if ($copy['status'] == 'lent')
			{
				Controller::loadModel('Loan');
				Controller::loadModel('User');
				$loan = $this->Loan->findByMaterial_id($copy['id']);
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
			$this->log("Adding the following copy ".print_r($copy, true));
			$rich_copies[$i] = $copy;
			$i++;
		}
		$this->log("These are the rich copies ".print_r($rich_copies, true));
		$this->set('copies', $rich_copies);
	}
}

?>

