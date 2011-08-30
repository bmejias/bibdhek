<?php

class BooksController extends AppController
{
	var $name = 'Books';

	function index()
	{
		$this->set('books', $this->Book->find('all'));
	}

	function add()
	{
		if (!empty($this->data))
		{
			if ($this->Book->save($this->data))
			{
				$this->Session->setFlash('The book has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
}

?>

