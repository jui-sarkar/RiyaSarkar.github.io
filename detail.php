<?php include('server.php')?>

<!DOCTYPE html>
<html>
<head>
  <title>Create Management</title>
  <header>
  <link rel="stylesheet" type="text/css" href="style3.css">
</head>
<body>
	
  <form method="post" action="detail.php">
  	<?php include('errors.php'); ?>
  	<div class="main">
	<center>
  	  <label>Send Money From Acount No:</label>
	  </center>
	  <center>
  	  <input type="text" name="from_account" value="<?php echo $from_account; ?>"><br>
<center>
		<label>Send Money To Account No:</label>
		<center>
		<input type="text" name="to_account" value="<?php echo $to_account; ?>"><br>
		</center>
                <label>Amount:</label>
				<center>
		<input type="text" name="transfer_amount"  value="<?php echo $transfer_amount; ?>"><br>
		</center>
<label>Date:</label>
<center>
<input type="date" name="date"  value="<?php echo $date; ?>">
</center>
</div>
  	<div class="input-group">
  	  <center>
<button type="submit" class="btn" name="reg_user"  >Transfer</button>
<button type="submit" class="btn" name="reg_userr"  >Reset</button>
<button type="submit" class="btn" name="reg_usere"  >Exit</button>
</center>
  	</div>
  	
  </form>
</body>
</header>
</html>