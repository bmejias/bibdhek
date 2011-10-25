<?php

class Material extends AppModel
{
	var $name = 'Material';

	/**
	 * Find the title of the book associated with the material_id
	 *
	 * @param serial $material_id 
	 * @return string book's title
	 */
	function get_book_title($material_id)
	{
		$query = "select books.title from books where books.id = ";
		$query.= "(select book_id from materials where id = ".$material_id.")";
		$result = $this->query($query);
		if (count($result) > 0)
			return $result[0][0]['title'];
		else
			return "ERROR - book not found";
	}
}

?>
