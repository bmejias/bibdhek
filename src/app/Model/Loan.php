<?php
/**
 * Author: Boriss Mejias <tchorix@gmail.com>
 */

class Loan extends AppModel
{
    var $name = 'Loan';

    static $RETURNED    = 'returned';

    /**
     * Find for user_id all copies being lent, or where there is still 
     * a fine to pay, or where there is a deposit to pay back
     *
     * @param serial $user_id
     * @return array active loans of the user
     */
    function get_from_user($user_id)
    {
        $query = "select * from loans where user_id=".$user_id;
        $query.= " and ";
        $query.= " (status = '".Copy::$LENT."' or fine > paid or deposit > 0)";
        $result = $this->query($query);
        for ($i = 0; $i < count($result); $i++)
            $result[$i] = $result[$i][0];
        return $result;
    }

    function add($copy_id, $user_id, $date_out, $date_return, $cd, $deposit)
    {
        $loan = array('Loan'=>array('copy_id'       => $copy_id,
                                    'user_id'       => $user_id,
                                    'date_out'      => $date_out,
                                    'date_return'   => $date_return,
                                    'cd'            => $cd,
                                    'deposit'       => $deposit,
                                    'status'        => Copy::$LENT));
        $this->create();
        return $this->save($loan);
    }

    function update($loan_id, $update_data)
    {
        $this->id = $loan_id;
        $this->set($update_data);
        return $this->save();
    }

    /* TODO: This function is not working */
    function get_status($loan_id)
    {
        $loan = $this->findById($loan_id);
        $this->debug("This is the STATUS we've found: ".print_r($loan, true));
        return $loan['Loan']['status'];
    }
}

?>
