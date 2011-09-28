<!-- File: /app/views/books/lend.ctp -->

<?php
include('../views/helpers/lib.php');
?>

<h2><?php echo $book['title']; ?></h2>

<?php
echo $this->Form->create('Book', array('type'=>'post', 'action'=>'do_lend'));
?>
<table>
	<tr>
		<td>Author</td>
		<td><?php echo $book['author']; ?></td>
	</tr>
	<tr>
		<td>Taal</td>
		<td><?php echo $book['lang']; ?></td>
	</tr>
	<tr>
		<td>Niveau</td>
		<td><?php echo ($book['level'] == '') ? '-' : $book['level']; ?></td>
	</tr>
	<tr>
		<td>Code</td>
		<td><?php echo $copy['code']; ?></td>
	</tr>
	<tr>
		<td>User</td>
		<td><?php echo $student; ?></td>
	</tr>
	<tr>
		<td>Date out</td>
		<td><?php echo $loan['date_out']; ?></td>
	</tr>
	<tr>
		<td>To return on</td>
		<td><?php echo $loan['date_return']; ?></td>
	</tr>
	<tr>
		<td>Date of return</td>
		<td>
			<?php
				echo $this->Form->input('date_in',
										array('type'  => 'text',
											  'label' => '',
											  'size'  => 10,
											  'value' => $today));
			?>
		</td>
	</tr>
	<?php if ($loan['cd']) : ?>
		<tr>
			<td>
				<?php
					echo $this->Form->input('cd', array('type'  => 'checkbox',
														'label' => 'CD',
														'checked'=> true));
				?>
			</td>
			<td>
				<?php
					$deposit = toCurrency($loan['deposit']);
					echo $this->Form->input('deposit',
											array('type'  => 'text',
												  'label' => 'Deposit',
												  'value' => $deposit,
												  'size'  => 5));
				?>
			</td>
		</tr>
	<?php endif; ?>
	<?php
		echo $this->Form->hidden('book', array('value'=>$book['id']));
		echo $this->Form->hidden('copy', array('value'=>$copy['id']));
		echo $this->Form->hidden('loan', array('value'=>$loan['id']));
	?>
</table>

<?php
echo $this->Form->submit('Return book', array('name'=>'data[Book][return]'));
echo $this->Form->submit('Cancel', array('name'=>'data[Book][cancel]'));
echo $this->Form->end();
?>

