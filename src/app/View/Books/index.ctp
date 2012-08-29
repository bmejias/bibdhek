<!-- File: /app/views/books/index.ctp -->

<h2> Books </h2>

<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Title</th>
            <th>Author</th>
            <th>Level</th>
            <th>Lang</th>
            <th>CD</th>
            <th>ISBN</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($books as $book): ?>
        <tr>
            <td><?php echo $book['Book']['id']; ?></td>
            <td><?php
                echo $this->Html->link($book['Book']['title'],
                                       'view?book_id='.$book['Book']['id']);
                ?>
            </td>
            <td><?php echo $book['Book']['author']; ?></td>
            <td><?php echo $book['Book']['level']; ?></td>
            <td><?php echo $book['Book']['lang']; ?></td>
            <td><?php echo $book['Book']['cd']; ?></td>
            <td><?php echo $book['Book']['isbn']; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br />
<p> <?php echo $this->Html->link('Back to actions', '/'); ?> </p>
