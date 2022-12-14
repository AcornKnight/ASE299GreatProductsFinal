<?php
  require_once(__DIR__.'/../Utils/settings.php');
  require_once(__DIR__.'/../Utils/utils.php');
  guard("user");

    // We don't have the password or email info stored in sessions so instead we can get the results from the database.
    $stmt = $con->prepare('SELECT userpass, Email FROM user WHERE UserID = ?');
    // In this case we can use the account ID to get the account info.
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($userpass, $Email);
    $stmt->fetch();
    $stmt->close();

    $addresses = $db->query('SELECT * FROM Address WHERE UserID='.$_SESSION['id']);
    $orders = $db->query('SELECT * FROM Invoice WHERE UserID='.$_SESSION['id']);
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Profile Page</title>
		<link href="../style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Great Products</h1>
        <a href="../index.php"><i class="fas fa-archive"></i>Main</a>
        <?php
          if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) {
            echo '<a href="../Admin/admin.php"><i class="fas fa-ad"></i>Admin</a>';
          }
         ?>
				<a href="../User/profile.php"><i class="fas fa-user-circle"></i>Profile</a>
        <a href="../Shop/cart.php"><i class="fas fa-cart-plus"></i>Cart</a>
				<a href="../Shop/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Profile Page</h2>
			<div>
				<p>Your account details are below:</p>
				<table>
					<tr>
						<td>Username:</td>
						<td><?=$_SESSION['name']?></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><?=$password?></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><?=$Email?></td>
					</tr>
				</table>
			</div>
            <div>
                <h3>Addresses:</h3>
                <a href="../User/address.php?action=create">ADD NEW ADDRESS</a>
                <table>
                    <?php
                        while($address=$addresses->fetch()) {
                            echo '<tr>';
                                echo '<td>'.$address['Street'].'</td>';
                                echo '<td>'.$address['City'].'</td>';
                                echo '<td>'.$address['State'].'</td>';
                                echo '<td>'.$address['Zip'].'</td>';
                                echo '<td>'.$address['Country'].'</td>';
                                echo '<td><a href="../User/address.php?action=update&AddressID='.$address['AddressID'].'">UPDATE</a></td>';
                                echo '<td><a href="../User/address.php?action=delete&AddressID='.$address['AddressID'].'">DELETE</a></td>';
                            echo '</tr>';
                        }
                    ?>
                </table>
            </div>
            <div>
                <h3>Orders:</h3>
                <table>
                    <?php
                        while($order=$orders->fetch()) {
                            echo '<tr>';
                                echo '<td>';
                                    echo '<td>'.$order['OrderID'].'</td>';
                                    echo '<td>'.$order['Status'].'</td>';
                                echo '</td>';
                            echo '</tr>';
                        }
                    ?>
                </table>
            </div>
		</div>
	</body>
</html>
