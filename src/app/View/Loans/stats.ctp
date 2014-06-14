<p>These are the statistics of the current year</p>

<h2>Total loans</h2>

<p><strong><?php echo $total_loans; ?></strong></p>

<h2>Top books lent</h2>

<ul>
<?php $i=0 ?>
<?php foreach ($top_books as $book): ?>
    <li> <?php echo $book[0]["counts"]." ".$book[0]["title"]." - ".$book[0]["author"] ?></li> 
    <?php
        $i++;
        if ($i == 10)
            break;
    ?>
<?php endforeach; ?>
</ul>

<br/><br/>
<h2>Top users lending books</h2>
<?php $i=0 ?>
<?php foreach ($top_users as $user): ?>
    <li> <?php echo $user[0]["counts"]." ".$user[0]["first_name"]." ".$user[0]["last_name"] ?></li> 
    <?php
        $i++;
        if ($i == 10)
            break;
    ?>
<?php endforeach; ?>
