<?php

include_once('../libs/lib.php');

class Loan extends AppModel
{
	var $name = 'Loan';

	/* Loan has the date to return a book. The amount of days of delay is
	 * computed comparing $now with the date to return, counting only working 
	 * days. Rule contains to entries: the amount of money to pay per fine, and 
	 * the amount of days to wait to apply a fine.
	 */
	function get_fine($id, $now)
	{
		Controller::loadModel('Rule');

		$loan = $this->findById($id);
		/* array() should contain the list of holidays and vacation */
		$days = getWorkingDays($loan['Loan']['date_return'], $now, array());
		$fine_days	= $this->Rule->findByRule('fine_days');
		$fine_money	= $this->Rule->findByRule('fine_money');
		/* assuming that amount is expressed in cents in the rule */
		$single_fine = $fine_money['Rule']['amount'] / 100;
		if ($days <= 0)
			return 0;
		else
			return floor($days / $fine_days['Rule']['amount']) * $single_fine;
	}
}

?>
