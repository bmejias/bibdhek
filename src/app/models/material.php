<?php

class Material extends AppModel
{
	var $name = 'Material';

	/**
	 * Find the title and id of the book associated with the material_id
	 *
	 * @param serial $material_id 
	 * @return array with book's title and book's id.
	 */
	function get_book_info($material_id)
	{
		$query = "select books.id, books.title from books where books.id = ";
		$query.= "(select book_id from materials where id = ".$material_id.")";
		$result = $this->query($query);
		//echo "This is what I got from the query\n".print_r($result, true);
		$to_return = array('id' => 0, 'title' => "ERROR - book not found");
		if (count($result) > 0)
		{
			$to_return['id']	= $result[0][0]['id'];
			$to_return['title']	= $result[0][0]['title'];
		}
		return $to_return;
	}
}

?>
