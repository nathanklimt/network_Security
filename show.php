<?php
	// Connects to the Database
	include('session.php');
	include('connect.php');
	$DB = connect();

	//if the login form is submitted
	if (isset($_GET['pid']) || isset($_GET['delpid'])) {
		if(!isset($_SESSION['hackme'])){
			die('You do not have access to do this!');
		}
	}

	if (!isset($_GET['pid'])) {
		if (isset($_GET['delpid'])){
			if (!array_key_exists('token', $_GET) || $_GET['token'] != $wicked_awesome_token) {
				die('<p>CSRF attack detected</p>');
			}

			//mysqli_query($DB, "DELETE FROM threads WHERE id = '".$_GET['delpid']."'") or die(mysqli_error($DB));
			$_stmt = mysqli_prepare($DB, "DELETE FROM threads WHERE id = ? AND username = ?");
			mysqli_stmt_bind_param($_stmt, 'ss', $_GET['delpid'], $_SESSION['hackme']);
			mysqli_stmt_execute($_stmt);
			mysqli_stmt_close($_stmt);
		}
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
	if(!isset($_SESSION['hackme'])){
		 die('Why are you not logged in?!');
	}else
	{
		print("<p>Logged in as <a>$_SESSION[hackme]</a></p>");
	}
?>
<?php
	$_resultArray = [];
	//$threads = mysqli_query($DB, "SELECT * FROM threads WHERE id = '".$_GET['pid']."'") or die(mysqli_error($DB));
	$_stmtT = mysqli_prepare($DB, "SELECT * FROM threads WHERE id = ?");
	mysqli_stmt_bind_param($_stmtT, 'i', $_GET['pid']);
	mysqli_stmt_execute($_stmtT);
	$resultT = mysqli_stmt_get_result($_stmtT);
	while ($row = mysqli_fetch_array($resultT)) {
		$_resultArray[] = $row;
	}
	mysqli_stmt_close($_stmtT);
	foreach ($_resultArray as $thisthread) {
	//while($thisthread = mysqli_fetch_array( $threads )){
?>
	<div class="post">
	<div class="post-bgtop">
	<div class="post-bgbtm">
		<h2 class="title"><a href="show.php?pid=<?php echo $thisthread['id'] ?>"><?php echo $thisthread['title']?></a></h2>
							<p class="meta"><span class="date"> <?php echo date('l, d F, Y',$thisthread['date']) ?> - Posted by <a href="#"><?php echo $thisthread['username'] ?> </a></p>

         <div class="entry">

            <?php echo $thisthread['message'] ?>

		 </div>

	</div>
	</div>
	</div>

    <?php
		if ($_SESSION['hackme'] == $thisthread['username'])
		{
	?>
    	<a href="show.php?delpid=<?php echo $thisthread['id']?>&token=<?=$wicked_awesome_token?>">DELETE</a>
    <?php
		}
	?>

<?php
}
	include('footer.php');
?>
</body>
</html>
