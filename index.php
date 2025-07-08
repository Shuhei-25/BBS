<?php

  date_default_timezone_set("Asia/Tokyo");

  $comment_array = array();
  $pdo = null;
  $stmt = null;
  $error_messages = array();

//データベース
try {
  $pdo = new PDO('mysql:host=localhost;dbname=PHP bbs', "root", "root");
} catch (PDOException $e) {
  echo $e->getMessage();
}

//フォームを打ち込んだ時
  if (!empty($_POST["submitButton"])){

    //バディデーション名前
    if(empty($_POST["username"])) {
      echo "Name is empty";
      $error_messages["username"] = "Name is empty";
    }

    //バディデーションコメント
        if(empty($_POST["comment"])) {
          echo "Comment is empty";
          $error_messages["comment"] = "Comment is empty";
        }



    if(empty($error_messages)) {
      $postDate = date("Y-m-d H:i:s");


    try {
      $stmt = $pdo->prepare("INSERT INTO `bbs-table` (`username`, `comment`, `postDate`) VALUES ( :username, :comment, :postDate);");
      $stmt->bindParam(':username', $_POST['username']);
      $stmt->bindParam(':comment', $_POST['comment']);
      $stmt->bindParam(':postDate', $postDate);

      $stmt->execute();

    } catch  (PDOException $e) {
      echo $e->getMessage();
    }
  }

    }
    
  
  //データベースからデータを取得する
  $sql = "SELECT `id`,`username`,`comment`,`postDate` FROM `bbs-table`;";
  $comment_array = $pdo->query($sql);



  


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP Bulletin Board System</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1 class="title">PHP Bulletin Board System</h1>
  <hr>
  <div class="boardWrapper">
    <section>
      <article>
        <?php foreach( $comment_array as $comment): ?>
        <div class="wrapper">
          <div class="nameArea">
            <span>Name : </span>
            <p class="username"><?php echo htmlspecialchars($comment["username"]); ?></p>
            <time>:<?php echo htmlspecialchars($comment["postDate"]); ?></time>
          </div>
          <p class="comment"><?php echo htmlspecialchars($comment["comment"]); ?></p>
        </div>
      </article>
      <?php endforeach ; ?>
    </section>
      <form class="formWrapper" method="POST">
        <div>
          <input type="submit" value="SAVE" name="submitButton">
          <label for="N">Name : </label>
          <input type="text" name="username">
        </div>
        <div>
          <textarea class="commentTextArea" name="comment"></textarea>
        </div>
      </form>

  </div>
  
</body>
</html>