<table>
    <thead>
        <tr>
            <!-- <th>Id</th> -->
            <th>Title</th>
            <th>Author</th>
            <th>Level</th>
            <th>Lang</th>
            <th>CD</th>
            <!-- <th>ISBN</th> -->
        </tr>
    </thead>
    <tbody>
        <?php foreach ($books as $book): ?>
        <tr>
            <!-- <td><?php echo $book['Book']['id']; ?></td> -->
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
            <!-- <td><?php echo $book['Book']['isbn']; ?></td> -->
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

