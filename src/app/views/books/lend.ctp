<!-- File: /app/views/books/lend.ctp -->

<?php
/* Move the following function to a standard place for reusable code */
function toCurrency($amount)
{
	$no_cents = $amount * 100;
	if ($no_cents % 100 == 0)
		return $amount.".00";
	elseif ($no_cents % 10 == 0)
		return $amount."0";
	else
		return "".$amount;
}

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
	<?php if ($book['cd']) : ?>
		<tr>
			<td>
				<?php
					echo $this->Form->input('cd', array('type'  => 'checkbox',
														'label' => 'CD',
														'checked'=> true));
//														'checked'=> true,
//														'value' => 'cd'));
				?>
			</td>
			<td>
				<?php
					echo $this->Form->input('deposit',
											array('type'  => 'text',
												  'label' => 'Deposit',
												  'value' => toCurrency($deposit),
												  'size'  => 5));
				?>
			</td>
		</tr>
	<?php endif; ?>
	<tr>
		<td>
			<?php
				echo $this->Form->input('date_out', array('type'  => 'text',
														  'label' => 'Date out',
														  'size'  => 10,
														  'value' => $date_out));
			?>
		</td>
		<td>
			<?php
				echo $this->Form->input('date_return',
										array('type'  => 'text',
											  'label' => 'Return on',
											  'size'  => 10,
											  'value' => $date_return));
			?>
		</td>
	</tr>
	<tr>
		<td>User</td>
		<td>
			<?php
				echo $this->Form->hidden('book', array('value'=>$book['id']));
				echo $this->Form->hidden('copy', array('value'=>$copy['id']));
				echo $this->Form->select('user', $users);
			?>
		</td>
	</tr>
</table>

<?php
echo $this->Form->submit('Lend book', array('name'=>'data[Book][lend]'));
echo $this->Form->submit('Cancel', array('name'=>'data[Book][cancel]'));
echo $this->Form->end();
?>

