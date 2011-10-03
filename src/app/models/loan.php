<?php

class Loan extends AppModel
{
	var $name = 'Loan';

	function get_fine($id)
	{
		$loan = $this->findById($id);

	}
}

?>
