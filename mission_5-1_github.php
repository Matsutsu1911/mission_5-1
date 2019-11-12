<html>
    <head>
        <meta charset="UTF-8">
        <title>投稿フォーム</title>
        <style>
        form {
            margin-bottom: 20px;
        }
        </style>
    </head>

<?php
    $dsn ='mysql:dbname=;host=localhost';
    $user = '';
    $password = '';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    $sql = "CREATE TABLE IF NOT EXISTS posting"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,"
        . "password char(30)"
        .");";

    $stmt = $pdo->query($sql);


    /*                      削除機能                        */

    //削除フォームの送信の有無で処理を分岐
    if (!empty($_POST['dnum']) && !empty($_POST['delpass'])) {
        
        //入力データの受け取りを変数に代入
        $dnum = $_POST['dnum'];
        $delpass = $_POST['delpass'];
        $id = $dnum;
        $sql = "SELECT * FROM posting";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetchAll();
        foreach($result as $row) {

            //削除番号と行番号が一致しなければ書き込み
            if ($row['id'] == $dnum && $row['password'] == $delpass) {
                $sql = "DELETE FROM posting WHERE id =:id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':id',$id,PDO::PARAM_STR);
                $stmt->execute();

            }else {
                   // echo "パスワードが違います。<br>";
            }
        }
    }

    /*                                    投稿機能                            */
    if (!empty($_POST['name']) && !empty($_POST['comment'])) {//名前とコメントが入力されている時

        //フォーム内が空でない場合に以下を実行する    
        $name = $_POST['name'];
        $comment = $_POST['comment'];
        $date = date('Y-m-d-H:i:s');
        $password = $_POST['password'];

        
        if(empty($_POST['editNO']) && !empty($_POST['password'])) {//パスワード付きの投稿処理

            //書き込み処理
            $sql = $pdo->prepare("INSERT INTO posting (name,comment,date,password) value (:name,:comment,:date,:password)");
            
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':password', $password, PDO::PARAM_STR);
            $sql -> execute();

        }                  

            /*                                 編集機能                         */

            //入力データの受け取りを変数に代入
/*            $editNO = $_POST['editNO'];

            $sql = "SELECT * FROM posting";
            $stmt = $pdo->query($sql);
            $result = $stmt->fetchAll();
            foreach($result as $data) {

                if($row['id'] == $editnum && $row['password'] == $password) {
                    $sql = "UPDATE posting SET name=:name,comment=:comment where id=:id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':name',$name,PDO::PARAM_STR);
                    $stmt->bindValue(':comment',$comment,PDO::PARAM_STR);
                    $stmt->bindValue(':id',$id,PDO::PARAM_INT);
                    $stmt->execute();

                }else{
                       // echo "パスワードが違います。<br>";
                }
            }*/
    }

        /*                      編集選択機能                     */

        //編集フォームの送信の有無で処理を分岐
        if (!empty($_POST['editnum']) && !empty($_POST['editpass'])) {

            //入力データの受け取りを変数に代入
            $editnum = $_POST['editnum'];
            $editpass = $_POST['editpass'];

            $sql = "SELECT * FROM posting";
            $stmt = $pdo->query($sql);
            $result = $stmt->fetchAll();
            foreach($result as $row) {

                //投稿番号と編集対象番号が一致したらその投稿の「名前」と「コメント」を取得
                if ($row['id'] == $editnum && $row['password'] == $editpass) {


                    $editnumber = $row['id'];
                    $editname = $row['name'];
                    $editcomment = $row['comment'];
                    $editpass = $row['password'];

                    //既存の投稿フォームに、上記で取得した「名前」と「コメント」の内容が既に入っている状態で表示させる
                    //formのvalue属性で対応
                }else {
                       // echo "パスワードが違います。<br>";
                }
            }
        }            
        /*                                 編集機能                         */
        if(!empty($_POST['editNO']) && !empty($_POST['password'])) {
            //入力データの受け取りを変数に代入
            $editNO = $_POST['editNO'];
            $name = $_POST['name'];
            $comment = $_POST['comment'];
            $id = $editNO;

            $sql = "SELECT * FROM posting";
            $stmt = $pdo->query($sql);
            $result = $stmt->fetchAll();
            foreach($result as $row) {

                if($row['id'] == $editNO && $row['password'] == $password) {
                    $sql = "UPDATE posting SET name=:name,comment=:comment where id=:id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':name',$name,PDO::PARAM_STR);
                    $stmt->bindValue(':comment',$comment,PDO::PARAM_STR);
                    $stmt->bindValue(':id',$id,PDO::PARAM_INT);
                    $stmt->execute();

                }else{
                    // echo "パスワードが違います。<br>";
                }
            }
        }
        ?>

    <form action="mission_5-1.php" method="post">
      <input type="text" name="name" placeholder="名前" value="<?php if(isset($editname)) {
          echo $editname;} ?>"><br>
      <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($editcomment)) {
          echo $editcomment;} ?>"><br>
      <input type="text" name="password" placeholder="パスワード">
      <input type="hidden" name="editNO" value="<?php if(isset($editnumber)) {echo $editnumber;} ?>">
      <input type="submit" name="submit" value="送信">
    </form>

    <form action="mission_5-1.php" method="post">
      <input type="text" name="dnum" placeholder="削除対象番号"><br>
      <input type="text" name="delpass" placeholder="パスワード">
      <input type="submit" value="削除">
    </form>

    <form action="mission_5-1.php" method="post">
      <input type="text" name="editnum" placeholder="編集対象番号"><br>
      <input type="text" name="editpass" placeholder="パスワード">
      <input type="submit" value="編集">
    </form>


    <?php
        $sql = "SELECT * FROM posting";
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){ //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].' ';
            echo $row['name'].' ';
            echo $row['comment'].' ';
            echo $row['date'].'<br>';
            echo "<hr>";
        }
    ?>
</body>
</html>