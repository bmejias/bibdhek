<?php

class LoansController extends AppController
{
	var $name = 'Loans';

	/* pre: $this->data['Loan']['user'] should be a valid user */
	/* pre: $this->data['Loan']['copy'] should be a valid material */
	function lend()
	{
		$this->helpers[] = 'Time';
		$this->debug("THE DATA----------------------");
		$this->debug(print_r($this->data, true));
		$this->debug("THE BOOK----------------------");
		$this->debug(print_r($this->data['Loan'], true));
		$input = $this->data['Loan'];
		if (isset($input['lend']))
		{
			/* perform the loan */
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
		$this->redirect('../books/view?book_id='.$input['book']);
	}

	function return_book()
	{
		$input = $this->data['Loan'];
		if (isset($input['pay']))
			$this->pay_fine($input['loan_id'], $input['to_pay']);
		$this->redirect('../books/view?book_id='.$input['book_id']);
	}

	function return_book_from_book()
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

	/*-----------------------------------------------------------------------
	 * Functions to help the controllers
	 *-----------------------------------------------------------------------
	 */
	private function pay_fine($loan_id, $money)
	{
		$loan = $this->Loan->findById($loan_id);
		$this->Loan->id = $loan_id;
		$this->Loan->set(array('paid' => $loan['Loan']['paid'] + $money));
		$this->Loan->save();
	}
}


