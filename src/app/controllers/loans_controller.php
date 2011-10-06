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
		if (isset($input['pay']))
		{
			$payment = $this->pay_fine($input['loan_id'], $to_pay);
			$msg = "";
			if ($payment)
			{
				$msg	= "The user has paid ".toCurrency($to_pay)." euro.";
				$diff	= $input['fine'] - $to_pay;
				if ($diff > 0)
					$msg .= "<br/>He/she still needs to pay "
							.toCurrency($diff)." euro";
			}
			else
			{
				$this->log("ERROR: saving the payment of ".$to_pay);
				$this->log("error message is ".print_r($result, true));
				$msg = 'There was a problem with the payment.';
			}
			$this->Session->setFlash($msg);
		}
		elseif (isset($input['return']))
		{
			Controller::loadModel('Material');
			$this->Material->id = $input['copy_id'];
			$this->Material->saveField('status', 'available');
			$this->save_return($input['loan_id'],
								$input['date_in'], 
								$input['fine']);
		}
		$this->redirect('../books/view?book_id='.$input['book_id']);
	}

	/*-----------------------------------------------------------------------
	 * Functions to help the controllers
	 *-----------------------------------------------------------------------
	 */

	/**
	 * Modifies the loan to return the book. status => returned
	 *
	 * @param id $loan_id			identifier of the loan.
	 * @param date $date_in			date of effective return.
	 * @param number(4, 2) $money	final fine
	 * @return						result of the saving operation.
	 */
	private function save_return($loan_id, $date_in, $money)
	{
		$this->Loan->id = $loan_id;
		$this->Loan->set(array('date_in'	=> $date_in,
							   'status'		=> 'returned',
							   'fine'		=> $money));
		return $this->Loan->save();
	}

	/**
	 * Modifies the loan with the paid amount of money.
	 *
	 * @param id $loan_id			identifier of the loan.
	 * @param number(4, 2) $money	amount to pay.
	 * @return						result of the saving operation.
	 */ 
	private function pay_fine($loan_id, $money)
	{
		$loan = $this->Loan->findById($loan_id);
		$this->Loan->id = $loan_id;
		$this->Loan->set(array('paid' => $loan['Loan']['paid'] + $money));
		return $this->Loan->save();
	}
}


