<!-- File: /app/views/users/index.ctp -->

<?php
if ($result == 'error')
{
?>
    <h1>Wrong credentials!</h1>
<?php
}
else
{
?>
    <h1>Successful login!</h1>
<?php
}
?>
