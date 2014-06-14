<?php

/**
 * Author: Boriss Mejias <tchorix@gmail.com>
 */

include_once('../Lib/lib.php');

class LoansController extends AppController
{
    var $name = 'Loans';

    /* pre: data['Loan']['user_id'] should exist on the database */
    /* pre: data['Loan']['copy_id'] should exist on the database */
    function lend()
    {
        $this->helpers[] = 'Time';
        $this->debug("THE DATA----------------------");
        $this->debug(print_r($this->request->data, true));
        $this->debug("THE BOOK----------------------");
        $this->debug(print_r($this->request->data['Loan'], true));
        $input = $this->request->data['Loan'];
        if (isset($input['lend']))
        {
            /* perform the loan */
            Controller::loadModel('Copy');

            $copy_id    = $input['copy_id'];
            $user_id    = $input['user_id'];

            $add_loan = $this->Loan->add($copy_id,
                                         $user_id,
                                         $input['date_out'],
                                         $input['date_return'],
                                         $input['cd'],
                                         toNumber($input['deposit']));
            /* TODO: Modifying Loan and Copy should done in a transaction */
            if ($add_loan)
            {
                $this->Copy->setToLent($copy_id);
                $this->Session->setFlash('The book has been lent.');
            }
        }
        /* if the action is to cancel the loan, do nothing */
        /* elseif ($input['do'] == 'cancel') */
        /* redirect to book's view in any case */
        $this->redirect('../books/view?book_id='.$input['book_id']);
    }

    function extend_loan()
    {
    }

    function list_all()
    {
        Controller::loadModel('Copy');
        Controller::loadModel('User');
        Controller::loadModel('Book');
        $active_loans = $this->Loan->findAllByStatus(Copy::$LENT);
        $the_loans = array();
        $i = 0;
        foreach ($active_loans as $loan) {
            $the_loans[$i]['due'] = $loan['Loan']['date_return'];

            $user = $this->User->findById($loan['Loan']['user_id']);
            $name = $user['User']['first_name']." ".$user['User']['last_name'];
            $the_loans[$i]['user'] = $name;

            $copy = $this->Copy->findById($loan['Loan']['copy_id']);
            $book = $this->Book->findById($copy['Copy']['book_id']);
            $the_loans[$i]['book'] = $book['Book']['title'];
            $the_loans[$i]['author'] = $book['Book']['author'];
            $i += 1;
        }
        $this->set('active_loans', $active_loans);
        $this->set('the_loans', $the_loans);
    }

    function return_book()
    {
        $input  = $this->request->data['Loan'];
        $to_pay = toNumber($input['to_pay']);
        if (isset($input['full']))
        {
            $result = $this->pay_fine($input);
            $msg    = $result['msg']; 
            if ($result['db'])
            {
                $result = $this->save_return($input);
                $msg    = $msg."<br/>".$result['msg'];
            }
            $this->Session->setFlash($msg);
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
        $this->redirect($input['back_to']);
    }

    function stats()
    {
        $total_loans = $this->Loan->get_total_loans();
        $top_books = $this->Loan->get_top_books();
        $top_users = $this->Loan->get_top_users();
        $this->set('total_loans', $total_loans);
        $this->set('top_books', $top_books);
        $this->set('top_users', $top_users);
    }

    /*-----------------------------------------------------------------------
     * Functions to help the controllers - Move them to the Model?
     *-----------------------------------------------------------------------
     */

    private function save_return($input)
    {
        Controller::loadModel('Copy');
        $this->Copy->setToAvailable($input['copy_id']);

        $deposit = $input['deposit'];
        /* if cd is returned, return the deposit as well */
        if ($input['cd'])
            $deposit = $input['deposit'] - toNumber($input['deposit_back']);
        $update_data = array('status'     => Loan::$RETURNED,
                             'deposit'    => $deposit,
                             'cd'         => !$input['cd'],
                             'fine'       => $input['fine']);

        /* Update date_in only if the book hasn't been returned */
        $loan = $this->Loan->findById($input['loan_id']);
        if ($loan['Loan']['status'] != Loan::$RETURNED)
            $update_data['date_in'] = $input['date_in'];

        $update_loan = $this->Loan->update($input['loan_id'], $update_data);

        /* This is part of the feedback */
        $msg = "";
        if ($update_loan)
        {
            $msg = "The book has been returned.";
            if ($deposit > 0)
                $msg .= "<br/> The bib still needs to return "
                        .toCurrency($deposit)." euro.";
        }
        else
            $msg = 'There was a problem returning the book.';
        return array('db' => $update_loan, 'msg' => $msg);
    }

    private function pay_fine($input)
    {
        /* This is the functionality */
        $to_pay = toNumber($input['to_pay']);
        $loan   = $this->Loan->findById($input['loan_id']);
        $this->Loan->id = $input['loan_id'];
        $this->Loan->set(array('paid' => $loan['Loan']['paid'] + $to_pay));
        $db_result = $this->Loan->save();

        /* This is part of the feedback */
        $msg    = "";
        $diff   = $input['fine'];
        if ($db_result)
        {
            $msg    = "The user has paid ".toCurrency($to_pay)." euro.";
            $diff   = $input['fine'] - $to_pay;
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


