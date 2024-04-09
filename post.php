<?php
// Connects to the Database
	include('session.php');
	include('connect.php');
	$DB = connect();

	//if the login form is submitted
	if (isset($_POST['post_submit'])) {
		if(!isset($_SESSION['hackme'])){
			die('You do not have access to do this!');
		}

		$_POST['title'] = trim($_POST['title']);
		if(!$_POST['title'] | !$_POST['message']) {
			include('header.php');
			die('<p>You did not fill in a required field.
			Please go back and try again!</p>');
		}

		$_xssTitle = strip_tags($_POST['title']);
		$_xssMessage = strip_tags($_POST['message']);

		//mysqli_query($DB, "INSERT INTO threads (username, title, message, date) VALUES('".$_COOKIE['hackme']."', '". $_POST['title']."', '". $_POST[message]."', '".time()."')")or die(mysqli_error($DB));
		$_stmt = mysqli_prepare($DB, 'INSERT INTO threads (username, title, message, date) VALUES (?, ?, ?, ?)');
		mysqli_stmt_bind_param($_stmt, 'ssss', $_SESSION['hackme'], $_xssTitle, $_xssMessage, time());
		mysqli_stmt_execute($_stmt);
		mysqli_stmt_close($_stmt);

		header("Location: members.php");
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

            <h2 class="title">NEW POST</h2>
            <p class="meta">by <a href="#"><?php echo $_SESSION['hackme'] ?> </a></p>
            <p> do not leave any fields blank... </p>

            <form method="post" action="post.php">
			<input type="hidden" name="token" value="<?=$wicked_awesome_token?>">
            Title: <input type="text" name="title" maxlength="50"/>
            <br />
            <br />
            Posting:
            <br />
            <br />
            <textarea name="message" cols="120" rows="10" id="message"></textarea>
            <br />
            <br />
            <input name="post_submit" type="submit" id="post_submit" value="POST" />
            </form>
        </div>
    </div>
</div>

<?php
	include('footer.php');
?>
</body>
</html>
