<?php

class SearchController extends AppController
{
	var $name		= 'Search';
	var $uses		= array();
	var $useTable	= false;

	function index()
	{
	}

	function do_search()
	{
		$this->debug("Getting the form ".print_r($this->data, true));
		/* perform the search according to the parameters and redirect to the results page */
		$this->redirect('../search');
	}
}

?>

