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
			<td><?php echo $loan['book_title']; ?></td>
			<td><?php echo $loan['date_return']; ?></td>
			<td><?php echo $loan['status']; ?></td>
			<td><?php echo toCurrency($loan['fine'] - $loan['paid']); ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
