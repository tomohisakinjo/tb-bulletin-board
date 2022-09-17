<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>m02-1</title>
    <style>
    .forms{
        display: flex;
        flex-direction:column;
        align-items: center;
        justify-content: center;
    }
    input{
        margin-bottom: 10px;
    }
    p{
        text-align:center;
        font-size:20px;
    }
    </style>
</head>
<body>
    <?php
      $dsn = 'データベース名';        //データベースに接続
      $user = 'ユーザー名';
      $password = 'パスワード';
      $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
    ?>
    
    <?php
    $rename = "";
    $recomment = "";
    $repass = "";
    $renum = "";
    if(!empty($_POST["renum"]) && !empty($_POST["pass3"])) {    //編集番号入力時、入力ホームに記入される操作
      $id = $_POST["renum"];      //編集ホームからのデータ(編集番号)
      $pass = $_POST["pass3"];      //編集ホームからのデータ(パスワード)
      
      $sql = 'SELECT * FROM inputdata WHERE id=:id && pass=:pass';
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
      $stmt->execute();
      $results = $stmt->fetchAll(); 
        foreach ($results as $row){
           $renum = $row['id'];
           $rename = $row['name'];
           $recomment = $row['comment'];
           $repass = $row['pass'];
        }
    }
    ?>
    
    <p>夏休みの予定・思い出は何ですか？</p>
    <div class="forms">
    <form action="" method="post">                      <!--入力ホーム-->
        <input type="text" value="<?php echo $rename; ?>" name="name" placeholder="名前">
        <input type="text" value="<?php echo $recomment; ?>" name="comment" placeholder="コメント">
        <input type="text" value="<?php echo $repass; ?>" name="pass1" placeholder="パスワード">
        <input type="hidden" value="<?php echo $renum; ?>" name="renum" placeholder="編集番号">
        <input type="submit" name="submit">
    </form>
    
    <form action="" method="post">                      <!--削除ホーム-->
        <input type="num" name="deletenum" placeholder="削除対象番号">
        <input type="text" name="pass2" placeholder="パスワード">
        <input type="submit" name="submit" value="削除">
    </form>
    
    <form action="" method="post">                      <!--編集ホーム-->
        <input type="num" name="renum" placeholder="編集対象番号">
        <input type="text" name="pass3" placeholder="パスワード">
        <input type="submit" name="submit" value="編集">
    </form>
    </div>
    <hr>

    <?php
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["renum"])){  //編集ホーム入力後の入力ホーム
      $id = $_POST["renum"];
      $name = $_POST["name"];
      $comment = $_POST["comment"];
      $pass = $_POST["pass1"];
      
      $sql = 'UPDATE inputdata SET name=:name,comment=:comment,pass=:pass WHERE id=:id';
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->bindParam(':name', $name, PDO::PARAM_STR);
      $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
      $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
      $stmt->execute();
      
      $sql = 'SELECT * FROM inputdata';
      $stmt = $pdo->query($sql);
      $results = $stmt->fetchAll();
        foreach ($results as $row){
          echo $row['id'].',';
          echo $row['name'].',';
          echo $row['comment'].',';
          echo $row['date'].'<br>';
          echo "<hr>";
        }
    }
    
    elseif(!empty($_POST["name"]) && !empty($_POST["comment"])) {    //入力ホームに入力時動作
      $sql = $pdo -> prepare("INSERT INTO inputdata (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
      $sql -> bindParam(':name', $name, PDO::PARAM_STR);
      $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
      $sql -> bindParam(':date', $date, PDO::PARAM_STR);
      $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
      
      $name = $_POST["name"];
      $comment = $_POST["comment"];
      $date = date("Y年m月d日 H時i分s秒");
      $pass = $_POST["pass1"];
      $sql->execute();
     
      $sql = 'SELECT * FROM inputdata';
      $stmt = $pdo->query($sql);
      $results = $stmt->fetchAll();
        foreach ($results as $row){
          echo $row['id'].',';
          echo $row['name'].',';
          echo $row['comment'].',';
          echo $row['date'].'<br>';
          echo "<hr>";
        }
    }
    elseif(!empty($_POST["deletenum"]) && !empty($_POST["pass2"])){  //削除ホームに入力時
      $id = $_POST["deletenum"];      //削除ホームからのデータ(削除番号)
      $pass = $_POST["pass2"];              //削除ホームからのデータ(パスワード)
      
      $sql = 'delete from inputdata where id=:id && pass=:pass';
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
      $stmt->execute();

      $sql = 'SELECT * FROM inputdata';
      $stmt = $pdo->query($sql);
      $results = $stmt->fetchAll();
        foreach ($results as $row){
          echo $row['id'].',';
          echo $row['name'].',';
          echo $row['comment'].',';
          echo $row['date'].'<br>';
          echo "<hr>";
        }
    }
    else{         //それ以外の操作時表示
      $sql = "CREATE TABLE IF NOT EXISTS inputdata"
      ." ("
      . "id INT AUTO_INCREMENT PRIMARY KEY,"
      . "name char(32),"
      . "comment TEXT,"
      . "date TEXT,"
      . "pass TEXT"
      .");";
      $stmt = $pdo->query($sql);
      
      $sql = 'SELECT * FROM inputdata';
      $stmt = $pdo->query($sql);
      $results = $stmt->fetchAll();
        foreach ($results as $row){
          echo $row['id'].',';
          echo $row['name'].',';
          echo $row['comment'].',';
          echo $row['date'].'<br>';
          echo "<hr>";
        }
    }
    ?>
</body>