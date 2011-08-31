<!-- File: /app/views/books/index.ctp -->

<h1> Books </h1>

<table>
	<thead>
		<tr>
			<th>id</th>
			<th>title</th>
			<th>author</th>
			<th>cd</th>
			<th>level</th>
			<th>lang</th>
			<th>isbn</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($books as $book) : ?>
		<tr>
			<td><?php echo $book['Book']['id']; ?></td>
			<td><?php
				echo $this->Html->link($book['Book']['title'],
									   'view?book_id='.$book['Book']['id']);
				?>
			</td>
			<td><?php echo $book['Book']['author']; ?></td>
			<td><?php echo $book['Book']['cd']; ?></td>
			<td><?php echo $book['Book']['level']; ?></td>
			<td><?php echo $book['Book']['lang']; ?></td>
			<td><?php echo $book['Book']['isbn']; ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
