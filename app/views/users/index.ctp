<!-- File: /app/views/users/index.ctp -->

<h1> Users </h1>

<table>
	<tr>
		<td>id</td>
		<td>username</td>
		<td>first_name</td>
		<td>last_name</td>
		<td>email</td>
		<td>password</td>
	</tr>
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
</table>
