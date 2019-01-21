<?php
session_start();

// initializing variables
$from_account = "";
$to_account    = "";
$transfer_amount    = "";
$date    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'create_management');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $from_account = mysqli_real_escape_string($db, $_POST['from_account']);
$to_account = mysqli_real_escape_string($db, $_POST['to_account']);
$transfer_amount = mysqli_real_escape_string($db, $_POST['transfer_amount']);
$date = mysqli_real_escape_string($db, $_POST['date']);

 
  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($from_account)) { array_push($errors, "Account no is required"); }
  if (empty($to_account)) { array_push($errors, "Account no is required"); }
if (empty($transfer_amount)) { array_push($errors, "Amount is required"); }
if (empty($date)) { array_push($errors, "Date is required"); }

   $ress=0;
  if (count($errors) == 0) {
    $res = mysqli_query($db,"SELECT current_credit FROM users WHERE account_no=".$_POST['from_account']);
	$row = mysqli_fetch_row($res); 
    $current_credit=$row[0];
	  if( $current_credit-$transfer_amount< 0)
	  {
		
echo 'Insufficient amount to transfer';
                return false;

	  }
		else  {

  	$query = "INSERT INTO transfer (from_account,to_account,transfer_amount,date) 
  			  VALUES('$from_account','$to_account','$transfer_amount','$date')";
	
  	mysqli_query($db, $query);
  	$_SESSION['success'] = "Inserted";
  	header('location: detail.php');
	
	$result = mysqli_query($db, "UPDATE users SET current_credit = current_credit + " . $_POST['transfer_amount'] . " WHERE account_no = " . $_POST['to_account']);
    if ($result !== TRUE) {
        mysqli_rollback($db);  // if error, roll back transaction
    }
    
    // subtract $$ from source account
    $result = mysqli_query($db, "UPDATE users SET current_credit = current_credit - " . $_POST['transfer_amount'] . " WHERE account_no = " . $_POST['from_account']);
    if ($result !== TRUE) {
        mysqli_rollback($db);  // if error, roll back transaction
    }

    // assuming no errors, commit transaction
    mysqli_commit($db);

// get account balances
// save in array, use to generate form
$result = mysqli_query($db, "SELECT * FROM users");
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

// close connection
mysqli_close($db);

  
}
}
}

if (isset($_POST['reg_usere'])) {
header('location: home.php');

}
?>