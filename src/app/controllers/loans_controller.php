<?php
include_once('../libs/lib.php');

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
		$input	= $this->data['Loan'];
		$to_pay	= toNumber($input['to_pay']);
		if (isset($input['full']))
		{
			/* NOT IMPLEMENTED YET */
			$solde = $this->pay_fine($input);
		}
		elseif (isset($input['pay']))
		{
			$result = $this->pay_fine($input);
			$this->Session->setFlash($result['msg']);
		}
		elseif (isset($input['return']))
		{
			$result = $this->save_return($input);
			$this->Session->setFlash($result['msg']);
		}
		$this->redirect('../books/view?book_id='.$input['book_id']);
	}

	/*-----------------------------------------------------------------------
	 * Functions to help the controllers
	 *-----------------------------------------------------------------------
	 */

	private function save_return($input)
	{
		/* This is the functionality */
		Controller::loadModel('Material');
		$this->Material->id = $input['copy_id'];
		$this->Material->saveField('status', 'available');
		$this->Loan->id = $input['loan_id'];
		$this->Loan->set(array('date_in'	=> $input['date_in'],
							   'status'		=> 'returned',
							   'fine'		=> $input['fine']));
		$db_result = $this->Loan->save();

		/* This is part of the feedback */
		$msg = "";
		if ($db_result)
			$msg = "The book has been returned.";
		else
			$msg = 'There was a problem returning the book.';
		return array('db' => $db_result, 'msg' => $msg);

	}

	private function pay_fine($input)
	{
		/* This is the functionality */
		$to_pay	= toNumber($input['to_pay']);
		$loan	= $this->Loan->findById($input['loan_id']);
		$this->Loan->id = $input['loan_id'];
		$this->Loan->set(array('paid' => $loan['Loan']['paid'] + $to_pay));
		$db_result = $this->Loan->save();

		/* This is part of the feedback */
		$msg	= "";
		$diff	= $input['fine'];
		if ($db_result)
		{
			$msg	= "The user has paid ".toCurrency($to_pay)." euro.";
			$diff	= $input['fine'] - $to_pay;
			if ($diff > 0)
				$msg .= "<br/> He/she still needs to pay "
						.toCurrency($diff)." euro";
		}
		else
		{
			$this->log("ERROR: saving the payment of ".$to_pay);
			$this->log("error message is ".print_r($result, true));
			$msg = 'There was a problem with the payment.';
		}
		return array('db' => $db_result, 'msg' => $msg, 'diff' => $diff);
	}
}


