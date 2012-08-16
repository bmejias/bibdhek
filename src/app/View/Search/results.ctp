<?php
include_once('../View/Pages/search.ctp');
?>

<?php
/* --- BEGIN RESULTS FOR BOOKS ---*/
if (count($books) > 0) :
?>
<h2> Books </h2>

<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Level</th>
            <th>Lang</th>
            <th>CD</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($books as $book) : ?>
        <tr>
            <td>
                <?php
                $url_book = '../books/view?book_id='.$book['Book']['id'];
                echo $this->Html->link($book['Book']['title'], $url_book);
                ?>
            </td>
            <td><?php echo $book['Book']['author']; ?></td>
            <td><?php echo $book['Book']['level']; ?></td>
            <td><?php echo $book['Book']['lang']; ?></td>
            <td><?php echo $book['Book']['cd']; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
endif;
/* --- END RESULTS FOR BOOKS ---*/
?>

<?php
/* --- BEGIN RESULTS FOR USERS ---*/
if (count($users) > 0) :
?>
<h2> Users </h2>

<table>
    <thead>
        <tr>
            <th>User</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) : ?>
        <tr>
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
