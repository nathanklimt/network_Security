<?php
include('session.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>hackme nklimt</title>
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
<?php
	include('connect.php');
	include('header.php');
	$DB = connect();
?>
<div class="post">
	<div class="post-bgtop">
		<div class="post-bgbtm">
        <h2 class = "title">hackme Registration</h2>
        <?php
		//if the registration form is submitted
		if (isset($_POST['submit'])) {
			if (!array_key_exists('token', $_POST) || $_POST['token'] != $wicked_awesome_token) {
				die('<p>CSRF attack detected</p>');
			}

			$_POST['uname'] = trim($_POST['uname']);
			if(!$_POST['uname'] | !$_POST['password'] |
				!$_POST['fname'] | !$_POST['lname']) {
 				die('<p>You did not fill in a required field.
				Please go back and try again!</p>');
 			}

			$password = $_POST['password'];
			if (strlen($password) <= 8) {
				die ('<p>Your password has to be longer than eight characters<p>');
			}
			
			$pspell = pspell_new("en");
			if (pspell_check($pspell, $password)) {
				die('<p>Your password cannot be a dictionary word<p>');
			}

			//$check = mysqli_query($DB, "SELECT * FROM users WHERE username = '".$_POST['uname']."'") or die(mysqli_error($DB));
			$_stmt = mysqli_prepare($DB, "SELECT * FROM users WHERE username = ?");
			mysqli_stmt_bind_param($_stmt, 's', $_POST['uname']);
			mysqli_stmt_execute($_stmt);
			mysqli_stmt_store_result($_stmt);

			//Gives error if user already exist
	 		$check2 = mysqli_stmt_num_rows($_stmt);

			mysqli_stmt_close($_stmt);

		if ($check2 != 0) {
			die('<p>Sorry, user name already exists.</p>');
		}
		else
		{
			$_encryptedPass = sha1($password);
			$_xssUsername = strip_tags($_POST['uname']);
			$_xssFirstname = strip_tags($_POST['fname']);
			$_xssLastname = strip_tags($_POST['lname']);

			$_stmt2 = mysqli_prepare($DB, 'INSERT INTO users (username, pass, fname, lname) VALUES (?, ?, ?, ?)');
			mysqli_stmt_bind_param($_stmt2, 'ssss', $_xssUsername, $_encryptedPass, $_xssFirstname, $_xssLastname);
			mysqli_stmt_execute($_stmt2);
			mysqli_stmt_store_result($_stmt2);
			mysqli_stmt_close($_stmt2);

			//mysqli_query($DB, "INSERT INTO users (username, pass, fname, lname) VALUES ('".$_POST['uname']."', '". $_encryptedPass ."', '". $_POST['fname']."', '". $_POST['lname'] ."');")or die(mysqli_error());

			echo "<h3> Registration Successful!</h3> <p>Welcome ". $_xssFirstname ."! Please log in...</p>";
		}
        ?>
        <?php
		}else{
        ?>
        	<form  method="post" action="register.php">
			<input type="hidden" name="token" value="<?=$wicked_awesome_token?>">
            <table>
                <tr>
                    <td> Username </td>
                    <td> <input type="text" name="uname" maxlength="20"/> </td>
                    <td> <em>choose a login id</em> </td>
                </tr>
                <tr>
                    <td> Password </td>
                    <td> <input type="password" name="password" minlength="8" maxlength="40"/> </td>
		</tr>
                <tr>
                    <td> First Name </td>
                    <td> <input type="text" name="fname" maxlength="25"/> </td>
                </tr>
                 <tr>
                    <td> Last Name </td>
                    <td> <input type="text" name="lname" maxlength="25"/> </td>
                </tr>
                <tr>
                    <td> <input type="submit" name="submit" value="Register" /> </td>
                </tr>
            </table>
            </form>
        <?php
		}
		?>
        </div>
    </div>
</div>
<?php
	include('footer.php');
?>
</body>
</html>
