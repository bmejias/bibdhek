<div class="notice">
<?php
$icon_style = array('height'=>40, 'style'=>'padding:10px;');
$small_icon_style = array('height'=>25);
$tiny_icon_style = array('height'=>15);
$dont_escape = array('escape'=>false);

$commit_icon = $this->Html->image('mochila-OK.png', $icon_style);
$destroy_icon = $this->Html->image('mochila-NOK.png', $icon_style);
$destroy_small_icon = $this->Html->image('mochila-NOK.png', $small_icon_style);
$remove_icon = $this->Html->image('NOK.png', $tiny_icon_style);
$commit_url = '/cart/commit';
$destroy_url = '/cart/destroy';
?>

<table>
    <tr>
    <?php
    // @pre: cart isn't empty
    $cart = $this->Session->read('cart');
    $copies = sizeof($cart['copies']);
    if ($copies == 0)
    {
    ?>
        <td>
            Session started for <strong><?php echo $cart['user']; ?></strong>
        </td>
        <td>
            <?php
            echo $this->Html->link('Cancel Session', $destroy_url);
            echo "&nbsp;";
            echo $this->Html->link($destroy_small_icon, $destroy_url, $dont_escape);
            ?>
        </td>
    <?php
    }
    else
    {
    ?>
        <td>
            <strong><?php echo $cart['user']; ?></strong> is lending the 
            following titles: 
            <ul>
            <?php
            foreach ($cart['copies'] as $copy):
                $remove_url = '/cart/remove?copy_id='.$copy['copy_id'];
                echo "<li>";
                echo "\"".$copy['title']."\" - <em>".$copy['author']."</em>";
                if ($copies > 1)
                {
                    echo $this->Html->link($remove_icon, $remove_url, $dont_escape);
                }
                echo "</li>";
            endforeach;
            ?>
            </ul>
        </td>
        <td>
            <?php
            echo $this->Html->link($commit_icon, $commit_url, $dont_escape);
            echo $this->Html->link($destroy_icon, $destroy_url, $dont_escape);
            ?>
        </td>
    <?php      
    }
    ?>
    </tr>
</table>

</div>
