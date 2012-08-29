<!-- File: /app/views/users/index.ctp -->

<h2> Users </h2>

<?php $cart = $this->Session->read('cart'); ?>
<table>
    <thead>
        <tr>
            <?php if ($cart == null): ?>
                <th width="100px">Take books</th>
            <?php endif; ?>
            <?php if ($mode == 'detailed') : ?> 
                <th>id</th>
                <th>username</th>
                <th>first_name</th>
                <th>last_name</th>
                <th>email</th>
                <th>password</th>
            <?php else : ?>
                <th>Name</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) : ?>
        <tr>
            <?php if ($cart == null): ?>
                <td>
                    <a href="cart/start?user_id=<?php echo $user['User']['id']; ?>">
                        <img src="img/mochila.png" height="30" align="right">
                    </a>
                </td>
            <?php endif; ?>
            <?php if ($mode == 'detailed') : ?> 
                <td><?php echo $user['User']['id']; ?></td>
                <td><?php echo $user['User']['username']; ?></td>
                <td><?php echo $user['User']['first_name']; ?></td>
                <td><?php echo $user['User']['last_name']; ?></td>
                <td><?php echo $user['User']['email']; ?></td>
                <td><?php echo $user['User']['password']; ?></td>
            <?php else : ?>
                <td>
                    <?php
                        $user_name = $user['User']['first_name']
                                        ." ".$user['User']['last_name'];
                        $url = 'view?user_id='.$user['User']['id'];
                        echo $this->Html->link($user_name, $url);
                    ?>
                </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<br />
<p> <?php echo $this->Html->link('Back to actions', '../pages/admin'); ?> </p>
