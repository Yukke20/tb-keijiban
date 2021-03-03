<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>mission_5-1</title>
    </head>
<?php
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE=> PDO::ERRMODE_WARNING));
$sql = "CREATE TABLE IF NOT EXISTS tbtest"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "comm TEXT,"
. "pw TEXT,"
. "time TEXT"
.");";
$stmt = $pdo->query($sql);
$n=$_POST["name"];
$e=$_POST["e"];
$c=$_POST["com"];
$p=$_POST["pass"];
$delnum=$_POST["delnum"];
$delpass=$_POST["delpass"];
$edinum=$_POST["edinum"];
$pa="<>";
if (strlen($n) && strlen($c) && strlen($p)){
    if (strlen($e)){
        $sql = 'SELECT * FROM tbtest';
	    $stmt = $pdo->query($sql);
	    $results = $stmt->fetchAll();
	    foreach($results as $row){
	        if ($row["id"]==$e && $row["pw"]==$p){
	            $id = $e; //変更する投稿番号
            	$name =$n;
            	$comm = $c; //変更したい名前、変更したいコメントは自分で決めること
            	$sql = 'UPDATE tbtest SET name=:name,comm=:comm WHERE id=:id';
            	$stmt = $pdo->prepare($sql);
            	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
            	$stmt->bindParam(':comm', $comm, PDO::PARAM_STR);
            	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
            	$stmt->execute();
	        }
	        $sql = 'SELECT * FROM tbtest';
	        $stmt = $pdo->query($sql);
	        $results = $stmt->fetchAll();
	        
	    }
	    foreach($results as $row){
	            echo $row["id"].$pa.
$row["name"].$pa.$row["comm"].$pa.$row["time"]."<br>";
	        }
    }else{
        $sql = $pdo -> prepare("INSERT INTO tbtest (name, comm, pw
,time) VALUES (:name, :comm, :pw, :time)");
	    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	    $sql -> bindParam(':comm', $comm, PDO::PARAM_STR);
	    $sql -> bindParam(':pw', $pw, PDO::PARAM_STR);
	    $sql -> bindParam(":time", $time,PDO::PARAM_STR);
	    $name=$n;
	    $comm=$c;
	    $pw=$p;
	    $time=date("Y/m/d h:i:s");
	    $sql -> execute();
	    $sql = 'SELECT * FROM tbtest';
	    $stmt = $pdo->query($sql);
	    $results = $stmt->fetchAll();
	    foreach ($results as $row){
	        echo $row["id"].$pa.
$row["name"].$pa.$row["comm"].$pa.$row["time"]."<br>";
	    }
    }
}elseif (strlen($delnum) && strlen($delpass)){
    $sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach($results as $row){
	    if ($row["id"]==$delnum && $delpass==$row["pw"]){
	        $id=$delnum;
	        $sql = 'delete from tbtest where id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	        $stmt->execute();
	    }else{
	        echo $row["id"].$pa.
$row["name"].$pa.$row["comm"].$pa.$row["time"]."<br>";
	    }
	}
}elseif (strlen($edinum)){
    $sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach($results as $row){
	    echo $row["id"].$pa.
$row["name"].$pa.$row["comm"].$pa.$row["time"]."<br>";
	    if ($row["id"]==$edinum){
	        $ediname=$row["name"];
	        $edicom=$row["comm"];
	    }
	}
}
?>   
<form action="" method="post">
    <b>投稿用フォーム</b><br>
        <input type="hidden" name="e" value="<?php if (strlen($edinum)){
echo $edinum;}?>">
        <input type="text" name="name" placeholder="氏名"
value="<?php if(strlen($ediname)){echo $ediname;}?>">
        <input type="text" name="com" placeholder="コメント"
value="<?php if (strlen($edicom)){echo $edicom;}?>">
        <input type="text" name="pass" placeholder="パスワード">
        <input type="submit" name="submit"><br>
    </form>
    <form action="" method="post">
    <b>削除用フォーム</b><br>
        <input type="number" name="delnum" placeholder="削除番号">
        <input type="text" name="delpass" placeholder="パスワード">
        <input type="submit" name="submit">
    </form>
    <form action="" method="post">
    <b>編集用フォーム</b><br>
        <input type="number" name="edinum" placeholder="編集番号">
        <input type="submit" name="submit">
    </form>
</html>