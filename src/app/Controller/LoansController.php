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

    /*-----------------------------------------------------------------------
     * Functions to help the controllers - Move them to the Model?
     *-----------------------------------------------------------------------
     */

    private function save_return($input)
    {
        /* This is the functionality */
        Controller::loadModel('Copy');
        $this->Copy->id = $input['copy_id'];
        $this->Copy->saveField('status', Copy::$AVAILABLE);

        $deposit = $input['deposit'];
        /* if cd is returned, return the deposit as well */
        if ($input['cd'])
            $deposit = $input['deposit'] - toNumber($input['deposit_back']);
        $this->Loan->id = $input['loan_id'];
        $this->Loan->set(array('date_in'    => $input['date_in'],
                               'status'     => 'returned',
                               'deposit'    => $deposit,
                               'cd'         => !$input['cd'],
                               'fine'       => $input['fine']));
        $db_result = $this->Loan->save();

        /* This is part of the feedback */
        $msg = "";
        if ($db_result)
        {
            $msg = "The book has been returned.";
            if ($deposit > 0)
                $msg .= "<br/> The bib still needs to return "
                        .toCurrency($deposit)." euro.";
        }
        else
            $msg = 'There was a problem returning the book.';
        return array('db' => $db_result, 'msg' => $msg);

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


