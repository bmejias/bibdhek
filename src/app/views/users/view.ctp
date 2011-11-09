<?php
include_once('../libs/lib.php');
?>
<h2><?php echo $user['first_name']." ".$user['last_name']; ?></h2>

<table>
	<thead>
		<tr>
			<th>Title</th>
			<th>Date to return</th>
			<th>Status</th>
			<th>Fine</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($loans as $loan) : ?>
		<tr>
			<td>
				<?php
					// I need book_id, user_id, copy_id, loan_id
					$url = '../books/return_it';
					$url.= '?book_id='.$loan['book_id'];
					$url.= '&copy_id='.$loan['copy_id'];
					$url.= '&user_id='.$loan['user_id'];
					$url.= '&loan_id='.$loan['id'];
					echo $this->Html->link($loan['book_title'], $url); 
				?>
			</td>
			<td><?php echo $loan['date_return']; ?></td>
			<td><?php echo $loan['status']; ?></td>
			<td><?php echo toCurrency($loan['fine'] - $loan['paid']); ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
