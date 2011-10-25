<h2><?php echo $user['first_name']." ".$user['last_name']; ?></h2>

<table>
	<thead>
		<tr>
			<td>Title</td>
			<td>Date to return</td>
			<td>Status</td>
			<td>Fine</td>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($loans as $loan) : ?>
		<tr>
			<td><?php echo $loan['title']; ?></td>
			<td><?php echo $loan['date_back']; ?></td>
			<td><?php echo $loan['status']; ?></td>
			<td><?php echo $loan['fine']; ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
