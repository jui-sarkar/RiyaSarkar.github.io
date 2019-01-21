<?php
// connect to database
$dbh = mysqli_connect('localhost', 'root', '', 'create_management') or die("Cannot connect");

// turn off auto-commit
mysqli_autocommit($dbh, FALSE);

// look for a transfer
if ($_POST['reg_user'] && is_numeric($_POST['transfer_amount'])) {
    // add $$ to target account
    $result = mysqli_query($dbh, "UPDATE users SET current_credit = current_credit + " . $_POST['transfer_amount'] . " WHERE account_no = " . $_POST['to_account']);
    if ($result !== TRUE) {
        mysqli_rollback($dbh);  // if error, roll back transaction
    }
    
    // subtract $$ from source account
    $result = mysqli_query($dbh, "UPDATE users SET current_credit = current_credit - " . $_POST['transfer_amount'] . " WHERE account_no = " . $_POST['from_account']);
    if ($result !== TRUE) {
        mysqli_rollback($dbh);  // if error, roll back transaction
    }

    // assuming no errors, commit transaction
    mysqli_commit($dbh);
}

// get account balances
// save in array, use to generate form
$result = mysqli_query($dbh, "SELECT * FROM users");
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

// close connection
mysqli_close($dbh);
?>
<html>
<head></head>
<body>

<h3>TRANSFER</h3>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
Transfer $ <input type="text" name="transfer_amount" size="5"> from

<select name="from">
<?php
foreach ($users as $a) {
    echo "<option value=\"" . $a['id'] . "\">" . $a['label'] . "</option>";    
}
?>
</select>

to

<select name="to_account">
<?php
foreach ($users as $a) {
    echo "<option value=\"" . $a['id'] . "\">" . $a['label'] . "</option>";    
}
?>
</select>

<input type="submit" name="reg_user" value="Transfer">

</form>

<h3>ACCOUNT BALANCES</h3>
<table border=1>
<?php
foreach ($accounts as $a) {
    echo "<tr><td>" . $a['label'] . "</td><td>" . $a['balance'] . "</td></tr>";    
}
?>
</table>
</body>
</html>