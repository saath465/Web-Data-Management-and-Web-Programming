<!DOCTYPE html>
<html>
<title>
  Message Board-chatty
</title>

<script type="text/javascript">
  function getMessage(rid){
    document.getElementById('sample'+rid).value = document.getElementById('posty').value;
  }
  </script>

<?php
//start a new Session
  session_start();
  if($_SESSION['Username'] == null) {
    header('Location:login.php');
  }
?>


  <body style='background-color:Teal;'>
  <h3 style='text-align:center;'>Welcome</h3>
  <h4 style='text-align:center;'>Message Board of Chatty Application</h4>
  <br/>
  <div style="margin-left:40px;">
    <div style="width:80%;">
      <br/>
      <form action="message_board.php" method="GET" name = "new">
        <p style="margin-left:160px;"> Enter post here </p>
        <textarea rows = "8px" cols = "60px" name = "new_post" id = "posty" placeholder="Something to Share ot Reply???"></textarea>
        <br/><br/>
        <input type="submit" value="Post Message" style="position:relative; margin-left:325px;">
      </form>

      </div>
      <br/>
      <div style="margin-left:360px">
        <form action="message_board.php" method="GET">
        <button type="submit" value="1" name="log_out">LogOut</button>
      </div>
    </div>
    <h3 style='text-align:center;'>Old Posts</h3>


    

  <?php
  //script for logout


    if(isset($_GET['log_out'])){
      $_SESSION['Username'] = null;
      session_destroy();
      header('Location:login.php');

    }
  ?>


  <?php
    //php script to handle the insertion of the posts into the database

    if (isset($_GET['new_post']) && !isset($_GET['reply_id'])) {
      if ($_GET['new_post'] != null) {
        $pid = uniqid();
        $usr_name = $_SESSION['Username'];
        $new_post = $_GET['new_post'];
        //create a new connection for connecting to the database
        //obtained from sample board.php file provided
        $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $q = $dbh->prepare('insert into posts values (?, null, ?, now(), ?)');
        $q->execute([$pid,$usr_name,$new_post]);
      }
    }
    ?>

    <?php
    //php script for handling the reply posts for the posts displayed and insert data into the database

      if (isset($_GET['reply_id']) && isset($_GET['text_samp'])){
        $rid = $_GET['reply_id'];
        $new_post_id = uniqid();
        $post_by = $_SESSION['Username'];
        $rpost = $_GET['text_samp'];
        //create a new connection for connecting to the database
        //obtained from sample board.php file provided
        $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $q = $dbh->prepare('insert into posts values (?, ?, ?, now(), ?)');
        $q->execute([$new_post_id,$rid,$post_by,$rpost]);
      }

    ?>


    <?php
    //script to print the database values connect to 2 tables to retrieve the user information and post information

      //obtained from sample board.php file provided
      $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
      $dbh->beginTransaction();
      $q = $dbh->prepare('select id, replyto, postedby, datetime, message
                              from posts
                                      order by datetime desc');
      $resu = $q->execute();
      $fu_q = $dbh->prepare('select * from users where username = ?');
      $fu_res = $fu_q->execute([$_SESSION['Username']]);
      $r = $fu_q->fetch();
      echo "<div>";
      echo "<pre >";
      while ($row = $q->fetch()) {
        $reply_to_id = $row['id'];
        echo "<p><b>Post ID: ".$row['id']."</b></p>";
        echo "<p>Reply to: ". $row['replyto']."</p>";
        echo "<p><i>User Name: ". $row['postedby']."</i></p>";
        echo "<p>Full Name: ". $r['fullname']."</p>";
        echo "<p>Date and Time of Post: ". $row['datetime']."</p>";
        echo "<p>Post: ". $row['message']."</p>";
        echo '<form action = "message_board.php" method = "GET">';
        echo "<input type = 'hidden' name = 'reply_id' value =".$reply_to_id.">";
        echo '<input type = "hidden" id ="sample'.$reply_to_id.'" name = "text_samp">';
        ?>
        <input type = "submit" value = "Reply" onClick="getMessage('<?php echo $reply_to_id ?>')" style="position:relative; margin-left:240px;">
        <?php
        echo "</form>";
        echo "<br/>";
        echo "-------------------------------------------------------------------------------------------------------------------------------------------------------------------";
        echo "<br/>";
      }
      echo "</pre>";
      echo "</div>";

  ?>



  </body>
</html>
