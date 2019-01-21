<?php
if (isset($_POST['reg_usere'])) {
header('location: home.php');
}

if(isset($_POST['search']))
{
    $valueToSearch = $_POST['valueToSearch'];
    // search in all table columns
    // using concat mysql function
    $query = "SELECT * FROM users WHERE CONCAT(id) LIKE '%".$valueToSearch."%'";
    $search_result = filterTable($query);
    
}

 else {
    $query = "SELECT * FROM users";
    $search_result = filterTable($query);
}

// function to connect and execute the query
function filterTable($query)
{
    $connect = mysqli_connect("localhost", "root", "", "create_management");
    $filter_Result = mysqli_query($connect, $query);
    return $filter_Result;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Create management</title>
            <link rel="stylesheet" type="text/css" href="style1.css">
        
    </head>
	<header>
    <body>
        
        <form action="view.php" method="post">
            <input type="text" name="valueToSearch" placeholder="Enter The ID" style="list-style-type:none;
	margin-top:25px;margin-left:10px;height:5vh;text-align:center;"/>
			<input type="submit" name="search" value="View Profile" style="list-style-type:none;height:5.5vh;width:10%;color:#000;"/>
			<div class="button">
            <a href="home.php" class="btn">Exit</a>
			
			
			
</div>
        <table>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
<th>Email</th>
<th>Phone No.</th>



                   
                </tr>     
           
          
      <!-- populate table from mysql database -->
                <?php while($row = mysqli_fetch_array($search_result)):?>
                <tr>
                    <td><?php echo $row['id'];?></td>
                     <td><?php echo $row['name'];?></td>
 <<td><?php echo $row['email'];?></td>
 <td><?php echo $row['phone_no'];?></td>

                    
              </tr>
				
                <?php endwhile;?>
            </table>
        </form>
        
				
				
    </body>
	</header>
</html>