<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_3-5</title>
    </head>
    <body>
<h1>DB掲示板</h1>

<?php
//DB接続設定
$dsn = '***********';
$user = '********';
$password = '********';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
 //データベース内にテーブルを作成
	$sql = "CREATE TABLE IF NOT EXISTS tbtest"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "dateat char(32),"
	. "pass char(32)"
	.");";
	$stmt = $pdo->query($sql);
    //4-2
    ?>


<?php 
    
    
    
    
    
    if($_POST["edi"]&&$_POST["edipass"]){
        
        $id = $_POST["edi"] ; // idがこの値のデータだけを抽出したい、とする
        $sql = 'SELECT * FROM tbtest WHERE id=:id ';
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetchAll();
        foreach ($results as $row){
		    if($_POST["edipass"]==$row['pass']){
                
                $oldname=$row['name'];
                $oldstr=$row['comment'];
                $edinum=$_POST["edi"];
                $oldpass=$_POST["edipass"];

            }
	    }
        
    }
    
    
    //投稿機能+編集機能
    if($_POST["name"]&&$_POST["str"]&&$_POST["password"]){
        if($_POST['edinum']){
            $edinum=$_POST['edinum'];
            
            $id = $edinum; //変更する投稿番号
	        $name = $_POST["name"];
	        $comment = $_POST["str"]; //変更したい名前、変更したいコメントは自分で決めること
	        $pass = $_POST["password"];
	        $dateat = date("Y年m月d日 H時i分s秒");
	        
	        $sql = 'UPDATE tbtest SET name=:name,comment=:comment,pass=:pass,dateat=:dateat WHERE id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
	        $stmt->bindParam(':dateat', $dateat, PDO::PARAM_STR);
	        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	        $stmt->execute();
            
            
            
            
            
        //編集機能    
        
        }else{
            $sql = $pdo -> prepare("INSERT INTO 
            tbtest (name, comment, dateat, pass) 
            VALUES (:name, :comment, :dateat, :pass)");
            
	        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	        $sql -> bindParam(':dateat', $dateat, PDO::PARAM_STR);
	        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	        $name = $_POST["name"];
	        $comment = $_POST["str"]; //好きな名前、好きな言葉は自分で決めること
	        $dateat = date("Y年m月d日 H時i分s秒");
	        $pass=$_POST["password"];
	        $sql -> execute();
        }
        
    }
    
    
    //削除機能
    if($_POST["del"]&&$_POST["delpass"]){
        $id = $_POST["del"] ; 
        $sql = 'SELECT * FROM tbtest WHERE id=:id ';
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetchAll();
        foreach ($results as $row){
		    if($_POST["delpass"]==$row['pass']){
            $id = $_POST["del"];
        	$sql = 'delete from tbtest where id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	        $stmt->execute();

            }
	    }
	    
                       
    }
       
    
    
    
    
    
?>


<form action="" method="post">
    <input type="text" name="edi" placeholder="編集したい番号" >
    <input type="text" name="edipass" placeholder="パスワード"> 
    <input type="submit" >
</form>

<form action="" method="post">    
    <input type="text" name="del" placeholder="削除したい投稿番号">
    <input type="text" name="delpass" placeholder="パスワード">
    <input type="submit" >
</form>
<form action="" method="post">
    <input type="text" name="name" placeholder="お名前"
    value="<?php if($oldname&&$oldstr){
                    echo $oldname;
                 }
            ?>">
    <input type="text" name="str" placeholder="コメント"
    value="<?php if($oldname&&$oldstr){
                    echo $oldstr;
                 }
            ?>">
    <input type="text" name="password" placeholder="パスワード"
    value="<?php if($oldname&&$oldstr){
                    echo $oldpass;
                 }
            ?>">    
    <input type="hidden" name="edinum" placeholder="編集時"
    value="<?php if($oldname&&$oldstr){
                    echo $edinum;
                 }
            ?>">       
    <input type="submit" >
</form>
<?PHP
//入力したデータレコードを抽出
    $sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].'<br>';
		echo $row['name'].'<br>';
		echo $row['comment'].'<br>';
		echo $row['dateat'].'<br>';
	echo "<hr>";
	}
?>


</body>
</html>
