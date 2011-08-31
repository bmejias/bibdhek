<!-- File: /app/views/books/view.ctp -->

<h2><?php echo $book['title']; ?></h2>

<table>
	<tr>
		<td>Author</td>
		<td><?php echo $book['author']; ?></td>
	</tr>
	<tr>
		<td>Taal</td>
		<td><?php echo $book['lang']; ?></td>
	</tr>
</table>

<h3>Copies</h3>
<table>
	<thead>
		<tr>
			<th>code</th>
			<th>status</th>
			<th>student</th>
			<th>boete</th>
			<th></th>
		<tr>
	</thead>
	<tbody>
		<?php foreach ($copies as $copy) : ?>
		<tr>
			<td><?php echo $copy['code']; ?></td>
			<td><?php echo $copy['status']; ?></td>
			<td><?php echo $copy['student']; ?></td>
			<td><?php echo $copy['fine']; ?></td>
			<td><?php
				if ($copy['status'] == 'available')
				{
					$action = 'lend';
					$submit_label = 'lend';
				}
				elseif ($copy['status'] == 'lent')
				{
					$action = 'return';
					$submit_label = 'return';
				}
				echo $this->Form->create('Book',
										 array('type'=>'post',
											   'action'=>$action));
				echo $this->Form->hidden('book', array('value'=>$book['id']));
				echo $this->Form->hidden('copy', array('value'=>$copy['id']));
				echo $this->Form->end($submit_label);
				?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

