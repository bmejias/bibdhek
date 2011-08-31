<!-- File: /app/views/books/lend.ctp -->

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

<?php
	echo $this->Form->create('Book', array('type'=>'post', 'action'=>'lend'));
?>
<table>
	<tr>
		<td>User</td>
		<td>
			<?php
				echo $this->Form->hidden('book', array('value'=>$book['id']));
				echo $this->Form->hidden('copy', array('value'=>$copy['id']));
				echo $this->Form->hidden('do', array('value'=>'lend'));
				echo $this->Form->select('user', $users);
			?>
		</td>
	</tr>
</table>
<?php
	echo $this->Form->end('Lend book');
?>

<?php
	echo $this->Form->create('Book', array('type'=>'post', 'action'=>'lend'));
	echo $this->Form->hidden('book', array('value'=>$book['id']));
	echo $this->Form->hidden('copy', array('value'=>$copy['id']));
	echo $this->Form->hidden('user', array('value'=>'foo'));
	echo $this->Form->hidden('do', array('value'=>'cancel'));
	echo $this->Form->end('Cancel');
?>

