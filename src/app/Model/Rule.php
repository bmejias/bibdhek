<?php

/**
 * Author Boriss Mejias <tchorix@gmail.com>
 */

include_once('../Lib/lib.php');

class Rule extends AppModel
{
    var $name = 'Rule';

    // Rules
    static $LEND_PERIOD = 'lend_period'; 

    // Returns the maximum date to return a book taken at $out_time
    function get_date_return($out_time)
    {
        $rule = $this->findByRule('lend');
        // assuming the amount of the rule corresponds to days
        return $out_time + $rule['Rule']['amount'] * 24 * 60 * 60;
    }

    // Returns the extension date starting from $the_date
    function get_date_extension($the_date)
    {
        $rule = $this->findByRule('lend');
    }

    // Returns the amount of deposit to lend a CD
    function get_deposit()
    {
        $rule = $this->findByRule('deposit');
        // assuming the amount of the rule corresponds to cents
        return $rule['Rule']['amount'] / 100;
    }

    /**
     * Compute fine based on date_return and now, count only working days.
     *
     * - 'fine_money' amount of money (in cents) to pay per fine.
     * - 'fine_days' amount of days to apply every fine.
     *
     */
    function get_fine($date_return, $now)
    {
        // TODO: array() should contain the list of holidays and vacation
        $days = getWorkingDays($date_return, $now, array());
        $fine_days  = $this->findByRule('fine_days');
        $fine_money = $this->findByRule('fine_money');
        // assuming that amount is expressed in cents in the rule
        $single_fine = $fine_money['Rule']['amount'] / 100;
        if ($days <= 0)
            return 0;
        else
            return floor($days / $fine_days['Rule']['amount']) * $single_fine;
    }
}

?>
