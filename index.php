<?php
	include('session.php'); 
	include('connect.php');
	if(isset($_SESSION['hackme']))
	{
		header("Location: members.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">


<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>hackme nklimt</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
<?php
	include('header.php');
?>
<div class="post">
	<div class="post-bgtop">
		<div class="post-bgbtm">
			<h2 class="title"><a href="#">Welcome to hackme </a></h2>
				<div class="entry">
		<?php
			if(!isset($_SESSION['hackme']))
				{
				?>
	           	<form method="post" action="members.php">
				<input type="hidden" name="token" value="<?=$wicked_awesome_token?>">
				<h2> LOGIN </h2>
				<table>
					<tr> <td> Username </td> <td> <input type="text" name="username" /> </td> </tr>
					<tr> <td> Password </td> <td> <input type="password" name="password" id="password" /> <input type="hidden" id="user-real-password" /> </td>  
                    <td> <input type="submit" name = "submit" value="Login" /> </td></tr>
				</table>
				</form>
					
				<hr style=\"color:#000033\" />
					
			<p></p><p>If you are not a member yet, please click <a href="register.php">here</a> to register.</p>
           <?php
				}
		?>
	</div>
	</div>
	</div>
</div>
<!-- end #sidebar -->
	<?php
		include('footer.php');
	?>
	<script>
/*
function crypt () {
	var password = document.getElementById("user-password");
  	var cipher = CryptoJS.AES.encrypt(password.value, "nklimt");
  	cipher = cipher.toString();
  	document.getElementById("user-real-password").value = cipher;
  	password.value = "";
  	return true;
}*/
</script>

</body>
</html>
