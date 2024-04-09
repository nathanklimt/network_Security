<?php
	// Connects to the Database
	include('session.php');
	include('connect.php');
	$DB = connect();

	//if the login form is submitted
	if (isset($_POST['submit'])) {
		if (!array_key_exists('token', $_POST) || $_POST['token'] != $wicked_awesome_token) {
			die('<p>CSRF attack detected</p>');
		}

		if (isset($_SESSION['loginAttempts']) && $_SESSION['loginAttempts'] >= 5) {
			die ('You have reached the maximum amount of login attemts, try again later!');
		}

		$_POST['username'] = trim($_POST['username']);
		if(!$_POST['username'] || !$_POST['password']) {
			die('<p>You did not fill in a required field.
			Please go back and try again!</p>');
		}

		$password = $_POST['password'];

		$_encryptedPass = sha1($password);

		//$check = mysqli_query($DB, "SELECT * FROM users WHERE username = '".$_POST['username']."'")or die(mysqli_error($DB));
		$_resultArray = [];
		$_stmt = mysqli_prepare($DB, "SELECT * FROM users WHERE username = ?");
		mysqli_stmt_bind_param($_stmt, 's', $_POST['username']);
		mysqli_stmt_execute($_stmt);
		mysqli_stmt_store_result($_stmt);
		$result = mysqli_stmt_get_result($_stmt);
		while ($row = mysqli_fetch_array($result)) {
			$_resultArray[] = $row;
		}
		//Gives error if user already exist
		$check2 = mysqli_stmt_num_rows($_stmt);

		mysqli_stmt_close($_stmt);

		if ($check2 == 0) {
			die("<p>Sorry, user name does not exists.</p>");
		}
		else
		{
			foreach ($_resultArray as $result) {
			 	//gives error if the password is wrong
				if ($_encryptedPass != $result['pass']) {
					if (!isset($_SESSION['loginAttempts'])) {
						$_SESSION['loginAttempts'] = 0;
					}
					$_SESSION['loginAttempts']++;
					die('Incorrect password, please try again.');
				}
			}

			//$hash = password_hash($password,
			//	PASSWORD_DEFAULT);

			$_SESSION['hackme'] = $_POST['username'];
			$_SESSION['loginAttempts'] = 0;
			//$_SESSION['hackme_pass'] = $password;
			header("Location: members.php");
		}
	}
		?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>hackme</title>
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
<?php
	include('header.php');
?>
<div class="post">
	<div class="post-bgtop">
		<div class="post-bgbtm">
        <h2 class = "title">hackme bulletin board</h2>
        	<?php
            if(!isset($_SESSION['hackme'])){
				 die('Why are you not logged in?!');
			}else
			{
				print("<p>Logged in as <a>$_SESSION[hackme]</a></p>");
			}
			?>
        </div>
    </div>
</div>

<?php
	$threads = mysqli_query($DB, "SELECT * FROM threads ORDER BY date DESC")or die(mysqli_error($DB));
	while($thisthread = mysqli_fetch_array( $threads )){
?>
	<div class="post">
	<div class="post-bgtop">
	<div class="post-bgbtm">
		<h2 class="title"><a href="show.php?pid=<?php echo $thisthread['id'] ?>"><?php echo $thisthread['title']?></a></h2>
							<p class="meta"><span class="date"> <?php echo date('l, d F, Y',$thisthread['date']) ?> - Posted by <a href="#"><?php echo $thisthread['username'] ?> </a></p>

	</div>
	</div>
	</div>

<?php
}
	include('footer.php');
?>
</body>
</html>