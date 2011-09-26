<?php

class Rule extends AppModel
{
	var $name = 'Rule';

	function get_date_return($out_time)
	{
		$rule = $this->findByRule('lend');
		/* assuming the amount of the rule corresponds to days */
		return $out_time + $rule['Rule']['amount'] * 24 * 60 * 60;
	}

	function get_deposit()
	{
		$rule = $this->findByRule('deposit');
		/* assuming the amount of the rule corresponds to cents */
		return $rule['Rule']['amount'] / 100;
	}
}

?>
