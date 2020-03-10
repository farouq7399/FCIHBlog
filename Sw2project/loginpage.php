<!DOCTYPE html>
<html>
<body>
<?php
$username="admin";
$password="admin";

session_start()
if (isset($_SESSION['loggedin'])&& $_SESSION['loggedin']== true){

header("location:hello.php")

}
if (isset($_POST['username']) && isset($_POST['password']) {
   if($_POST['username']==$username && $_POST['password']==$password)
   {
     $_SESSION['loggedin']=true;
     header("location:hello.php");
   }
}

  ?>
  <form method="post" action="loginpage.php">
     username<br/>
     <input type="text" name="username"><br/>
     password<br/>
     <input type="password" name="password"><br/>
     <input type="submit" value="Login!">
   </form>

</body>
</html>
