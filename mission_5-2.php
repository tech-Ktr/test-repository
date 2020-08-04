<!DOCTYPE HTML>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>mission_5-1</title>
</head>
    <br>
    <hi><font size="7">あなたの好きな食べ物は？</font></hi><br>
    <br>
    <font size="4">好きな食べ物を書いてください<br>
    記入方法：名前＋コメント＋好きなパスワード<br>
    ※正確に動作しているかの確認のためパスワードも表示しています。
    
    </font>
    <br>
    <hr>


<body>

<?php

            
    
    //受け取るデータ
    $yourname=$_POST["yourname"];
    $new_comment=$_POST["comment"];
    $new_submit=$_POST["new_submit"];
    
    $del_num=$_POST["del_num"];
    $del_submit=$_POST["del_submit"];
    
    $edit_num=$_POST["edit_num"];
    $edit_submit=$_POST["edit_submit"];
    $edited_num=$_POST["edited_num"];
    
    $new_password=$_POST["password"];
    $del_password=$_POST["del_password"];
    $edit_password=$_POST["edit_password"];


    //日付の取得
    $date=date("Y/m/d H:i:s");
    
    
    //テーブルがなければ作成
        // DB接続設定
        $dsn='データベース名';
        $user='ユーザー名';
        $password='パスワード';
        $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));  
        
        
        //テーブル作成
        $sql='CREATE TABLE IF NOT EXISTS mission5'
            	." ("
            	. "id INT AUTO_INCREMENT PRIMARY KEY,"
            	. "name char(32),"
            	. "comment TEXT,"
            	. "date DATETIME,"
            	. "pass TEXT"
            	.");";
        $stmt = $pdo->query($sql); 
                    	
            
            
    //投稿・編集で分岐
    if(isset($new_submit)){
   
           //空欄の処理
           if(!$yourname=="" or !$new_comment==""){
               
               
               //編集で分岐
                if(!empty($edited_num)){
                    
                                
                    //update
                    $id=$edited_num;
                            
                    //プリペアードステートメント
                    $sql='UPDATE mission5 SET name=:name,comment=:comment, date=:date, pass=:pass WHERE id=:id';
                    $stmt=$pdo->prepare($sql);
                        
                    //値をバインド
                    $stmt->bindParam(':name', $yourname, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $new_comment, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                    $stmt->bindParam(':pass', $new_password, PDO::PARAM_STR);
                                
                    //実行
                    $stmt->execute();
                           
                        
                    
                    
                }else{
                    
                    //新規投稿
                    $sql=$pdo->prepare("INSERT INTO mission5(name, comment, date, pass) VALUES(:name, :comment, :date, :pass)");
                    $sql -> bindParam(':name',$yourname, PDO::PARAM_STR);
                    $sql -> bindParam(':comment', $new_comment, PDO::PARAM_STR);
                    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                    $sql -> bindParam(':pass', $new_password, PDO::PARAM_STR);
                    $sql -> execute();
                    }
                
            }
           
   }
   
   
   //削除で分岐
   if(isset($del_submit) && $del_password!=""){
       
        //パス確認
        if(isset($del_password)){
       
            $id=$del_num;
           
            //selectで名前とコメントを取得
            $sql='SELECT * FROM mission5 WHERE id=:id';
            
            //プリペアードステートメント
            $stmt=$pdo->prepare($sql);  
            
            $stmt -> bindParam(':id',$id, PDO::PARAM_INT);
            
            //実行        
            $stmt->execute();
            
            //結果を格納
            $result=$stmt->fetch();
                
            //名前とコメントを取得
            $del_pw=$result['pass'];
            
            
                //削除フォームと取得したパスを比較
               if($del_password===$del_pw){
        
                    //一致でdelete  
                    $id=$del_num;
                    $sql='delete from mission5 where id=:id';
                    $stmt=$pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    
                }

        }
   }
   

   //編集フォームから送信時
   if(isset($edit_submit)){
       
        if(!empty($edit_num) && $edit_password!=""){
                    
                    
            //パス確認
            if(isset($edit_password)){
               
                $id=$edit_num;
                   
                //selectで名前とコメントを取得
                $sql='SELECT * FROM mission5 WHERE id=:id';
                
                //プリペアードステートメント
                $stmt=$pdo->prepare($sql);  
                    
                $stmt -> bindParam(':id',$id, PDO::PARAM_INT);
                    
                //実行        
                $stmt->execute();
                            
                //結果を格納
                $result=$stmt->fetch();
                                
                //名前とコメントを取得
                $edit_pw=$result['pass'];
                                
                                
                //削除フォームと取得したパスを比較
                if($edit_password==$edit_pw){
       
                        //名前とコメントを取得
                        $edit_yourname=$result['name'];
                        $edit_comment=$result['comment'];
                }
            }
        }
       
   }
 
 
   
      
?>

 <!--投稿フォーム-->
    <form action="" method="post">
       
        <!--投稿-->
        名前:<br>
        <input type="text" name="yourname" value="<?php if(isset($edit_yourname)){echo $edit_yourname;}?>">
        <br>
        
        コメント:<br>
        <input type="text" name="comment" value="<?php if(isset($edit_comment)){echo $edit_comment;}?>">
        <br>
        
        パスワード:<br>
        <input type="text" name=password value="<?php if(isset($edit_pw)){echo $edit_pw;} ?>">

        <input type="submit" name="new_submit">
        
        <br>
        
        <!--編集モード判定-->
        <input type="hidden" name="edited_num" value="<?php if(isset($edit_num)){echo $edit_num;} ?>">
        <br>
        
        <!--削除フォーム-->
        削除:<br>
        <input type="number" name="del_num" placeholder="削除したい番号"><br>
        パスワード:<br>
        <input type="text" name=del_password>   
        <input type="submit" name="del_submit">
        
        <br>
        <br>
        
        <!--編集用フォーム-->
        投稿を編集:<br>
        <input type="number" name="edit_num" placeholder="編集したい番号"><br>
        パスワード:<br>
        <input type="text" name=edit_password>   
        <input type="submit" value="編集" name="edit_submit">
        <br>
        
    


    </form>
    <br>
    <HR>
        
 <?php  
 
     // DB接続設定
    $dsn='データベース名';
    $user='ユーザー名';
    $password='パスワード';
    $pdo=new PDO($dsn, $user, $password, 
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            

    //テーブル一覧表示

	$sql ='SHOW TABLES';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[0];
		echo '<br>';
	}
	echo "<hr>";
    
 
    //内容表示
    
        //select
        $sql='SELECT * FROM mission5';
        $stmt=$pdo->query($sql);
        $results=$stmt->fetchAll();
        foreach($results as $row){
            
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].',';
            echo $row['pass'].'<br>';
            
            echo "<hr>";
            
        }
        

?>

</body>
</html>