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
        $query = "SELECT * FROM loans WHERE user_id=".$user_id;
        $query.= " AND ";
        $query.= " (status = '".Copy::$LENT."' OR fine > paid OR deposit > 0)";
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

    /* The following functions are not assuming any range of date */
    function get_total_loans()
    {
        $query = "SELECT COUNT(id) FROM loans";
        $result = $this->query($query);
        return $result[0][0]['count'];
    }

    function get_top_books()
    {
        $query_loans = "SELECT l.id AS loan_id, b.id AS book_id ";
        $query_loans.= "FROM loans l, books b, copies c ";
        $query_loans.= "WHERE l.copy_id=c.id AND c.book_id=b.id";

        $count_by_book = "SELECT COUNT(lc.loan_id) AS counts, lc.book_id ";
        $count_by_book.= "FROM (".$query_loans.") lc ";
        $count_by_book.= "GROUP BY lc.book_id";

        $query = "SELECT st.counts AS counts, bo.title AS title, ";
        $query.= "bo.author AS author ";
        $query.= "FROM books bo, (".$count_by_book.") st ";
        $query.= "WHERE st.book_id=bo.id ";
        $query.= "ORDER BY counts DESC";

        $result = $this->query($query);
        return $result[0];
    }

    function get_top_users()
    {
        return array();
    }
}

?>
