<?php
/**
 * Author: Boriss Mejias <tchorix@gmail.com>
 */

class Loan extends AppModel
{
    var $name = 'Loan';

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

    function add_loan($copy_id, $user_id, $date_out, $date_return, $cd, $deposit)
    {
        $loan = array('Loan'=>array('copy_id'       => $copy_id,
                                    'user_id'       => $user_id,
                                    'date_out'      => $date_out,
                                    'date_return'   => $date_return,
                                    'cd'            => $cd,
                                    'deposit'       => $deposit,
                                    'status'        => 'lent'));
        $this->create();
        return $this->save($loan);
    }
}

?>
