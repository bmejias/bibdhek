<?php

class SearchController extends AppController
{
	var $name		= 'Search';
	var $uses		= array();
	var $useTable	= false;

	function do_search()
	{
		
		Controller::loadModel('Book');
		Controller::loadModel('User');
		$this->debug("Getting the form ".print_r($this->data, true));
		$the_query		= strtolower($this->data['search']['query']);
		$book_fields	= array('Book.title',
								'Book.author',
								'Book.level');
		$user_fields	= array('User.username',
								'User.first_name',
								'User.last_name');
		$books	= $this->search_in($this->Book, $book_fields, $the_query);
		$users	= $this->search_in($this->User, $user_fields, $the_query);
		$this->set('books', $books);
		$this->set('users', $users);
		$this->render('../search/results');
	}

	function index()
	{
	}

	function results()
	{
		$this->log("The controller of the results has been called!");
	}

	/*-----------------------------------------------------------------------
	 * Functions to help the controllers
	 *-----------------------------------------------------------------------
	 */
	private function search_in($model, $fields, $full_query)
	{
		/* Implementation based on "Simple search in CakePHP using find 
		 * conditions", posted by saravana at CakePHP's bakery.
		 */
		$conditions		= array();
		$or_conditions	= array();
		$final_conditions = array();

		$queries = explode(" ", $full_query);
		foreach ($fields as $f)
		{
			array_push($conditions, array("LOWER($f) Like" => "%$full_query%"));
			for ($i = 0; $i < count($queries); $i++)
			{
				if ($queries[$i] != "")
					array_push($conditions,
							   array("LOWER($f) Like" => "%".$queries[$i]."%"));
			}

			array_push($or_conditions, array('OR' => $conditions));
			$conditions = array();
		}
		$final_conditions = array('OR' => $or_conditions);
		$results = $model->find('all',
								array('conditions' => $final_conditions));
		return $results;
	}

}

?>

