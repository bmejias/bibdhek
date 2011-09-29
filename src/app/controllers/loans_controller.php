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

}


