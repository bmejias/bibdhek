<?php
include_once('../View/Pages/search.ctp');
?>

<?php
/* --- BEGIN RESULTS FOR BOOKS ---*/
if (count($books) > 0) :
?>
<h2> Books </h2>
<?php
    include('../View/Books/table.ctp');
endif;
/* --- END RESULTS FOR BOOKS ---*/
?>

<?php
$cart = $this->Session->read('cart');

/* --- BEGIN RESULTS FOR USERS ---*/
if (count($users) > 0) :
?>
<h2> Users </h2>

<table>
    <thead>
        <tr>
            <?php if ($cart == null): ?>
                <th width="100px">Take books</th>
            <?php endif; ?>
            <th>User</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) : ?>
        <tr>
            <?php if ($cart == null): ?>
                <td>
                    <a href="../cart/start?user_id=<?php echo $user['User']['id']; ?>">
                        <img src="../img/mochila.png" height="30" align="right">
                    </a>
                </td>
            <?php endif; ?>
            <td>
                <?php
                $url_user   = '../users/view?user_id='.$user['User']['id'];
                $name_user  = $user['User']['first_name'].
                              " ".$user['User']['last_name'];
                echo $this->Html->link($name_user, $url_user);
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
endif;
/* --- END RESULTS FOR USERS ---*/
?>

<?php
if (count($books) + count($users) == 0) :
?>
    <h3> No results found </h3>
<?php
endif;
?>
