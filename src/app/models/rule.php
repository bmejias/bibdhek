<?php

class Rule extends AppModel
{
	var $name = 'Rule';

	function get_date_in($out_time)
	{
		$rule = $this->findByRule('lend');
		/* we are assuming the amount of the rule corresponds to days */
		return $out_time + $rule['Rule']['amount'] * 24 * 60 * 60;
	}
}

?>
