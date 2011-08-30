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
				$book_id = $this->Book->id;
				$copies = 1;
				if ($this->data['Book']['copies'] > 1)
					$copies = $this->data['Book']['copies'];
				Controller::loadModel('Material');
				for ($i = 1; $i <= $copies; $i++)
				{
					$new_material = array('Material'=>
										  array('book_id'=>$book_id,
												'status'=>'available',
												'code'=>$i));
					$this->Material->create();
					$result = $this->Material->save($new_material);
					$this->log("Saving material returned ".print_r($result,
																   true));
				}
				$this->Session->setFlash('The book has been saved.');
				$this->redirect(array('action' => 'index'));
			}
			$this->redirect(array('action' => 'index'));
		}
	}
}

?>

