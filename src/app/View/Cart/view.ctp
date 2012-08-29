<h2>
<?php echo $this->Html->image('mochila.png', array('height'=>30)); ?> Shopping Cart
</h2>

<?php $cart = $this->Session->read('cart'); ?>

<p>
    <strong>
        <?php echo $cart['user']; ?>
    </strong>
</p>

<? if (sizeof($cart['copies']) == 0): ?>

    <p>There are no books selected</p>

    <p>
    <?php echo $this->Html->link('Cancel Shopping Cart', '/cart/destroy'); ?>
    </p>

<? else: ?>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart['copies'] as $copy): ?>
            <tr>
                <td><?php echo $copy['title']; ?></td>
                <td><?php echo $copy['author']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
    $icon_style     = array('height'=>80, 'style'=>'padding:20px;');
    $commit_icon    = $this->Html->image('mochila-OK.png', $icon_style);
    $destroy_icon   = $this->Html->image('mochila-NOK.png', $icon_style);
    $commit_url     = '/cart/commit';
    $destroy_url    = '/cart/destroy';
    $dont_escape    = array('escape'=>false);

    echo $this->Html->link($commit_icon, $commit_url, $dont_escape);
    echo $this->Html->link($destroy_icon, $destroy_url, $dont_escape);
    ?>

<? endif; ?>

<p>
<?php
    echo $this->Html->link('Back to search', '/');
?>
</p>
