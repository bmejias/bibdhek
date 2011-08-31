<!-- File: /app/views/users/index.ctp -->

<h2> Users </h2>

<table>
	<thead>
		<tr>
			<th>id</th>
			<th>username</th>
			<th>first_name</th>
			<th>last_name</th>
			<th>email</th>
			<th>password</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($users as $user) : ?>
		<tr>
			<td><?php echo $user['User']['id']; ?></td>
			<td><?php echo $user['User']['username']; ?></td>
			<td><?php echo $user['User']['first_name']; ?></td>
			<td><?php echo $user['User']['last_name']; ?></td>
			<td><?php echo $user['User']['email']; ?></td>
			<td><?php echo $user['User']['password']; ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<br />
<p> <?php echo $this->Html->link('Back to actions', '../pages/admin'); ?> </p>
