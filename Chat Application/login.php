<!DOCTYPE html>
<html>
<title>
  PhP Chatty
</title>

<?php
  session_start();
  //start session for the application
  ?>

<body style="background-color:Teal">
  <h2 style="text-align:center"> Chatty</h2>
  <h5 style="text-align:center"> A Chat Application Just Like Other's </h5>
  <br/><br/><br/>
  <div style="margin-left:30px;">
  <form action = "login.php" method = "GET">
    <label><b><i>Enter Username and Password to enter into the chatBox</i></b></label>
    <br/><br/><br/>
    Username: <input type="text" name="Username" placeholder="user_name">
    <br/><br/>
    Password: <input type="password" name="Password" placeholder="pwd" style="margin-left:3px">
    <br/><br/><br/>
    <input type="submit" value="submit" style="position:relative; margin-left:4in">
  </form>
</div>


  <?php
    if(isset($_GET['Username']) && isset($_GET['Password'])){
      $usr = $_GET['Username'];
      $pwd = $_GET['Password'];
      $_SESSION['Username'] = $usr;
      //connect to database to check the existance of the user in the database.
      $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
      $q = $dbh->prepare('select * from users where username = ? and password = ?');
      $q->execute([$usr,md5($pwd)]);
      $res = $q->fetch();

      //if the user exists in the database
      if($res) {
        header('Location:message_board.php');
      }
      else{
        //if user is not present in the database
        echo '<br/>';
        echo 'Please enter correct username and password';
      }
    }?>
  </body>
</html>
