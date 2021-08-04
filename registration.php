<?php
require_once __DIR__ . '/inc/functions.php';
include __DIR__ . '/inc/header.php';
?>
<form method='post' action='registration.php' class='loginform'>
    <div style="text-align: center;">
        <b>ユーザー名とパスワードを設定してください</b>
    </div>

    <label for="username">ユーザー名:</label>
    <input type='text' name='username'>

    <label for="password">パスワード:</label>
    <input type='password' name='password'>

    <input type='submit' value='登録する'>
    
    <input type='button' onclick="location.href='./login.php'" value='ログイン画面に戻る'>
</form>

<?php
if ((empty($_POST['username'])) || (empty($_POST['password']))) {
    echo "<div style='text-align: center'>ユーザー名、パスワードを入力してください。</div>";
    exit;
}

if((!empty($_POST['username'])) || (!empty($_POST['password']))) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    exit;
}

if(!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i/u', $_POST['username'])) {
    echo "<div style='text-align: center'>ユーザー名は半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。</div>";
    exit;
}
if (!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i/u', $_POST['password'])) {
    echo "<div style='text-align: center'>パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。</div>";
    exit;
}

try {
    $dbh = db_open();
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $dbh->prepare($sql);
    // $stmt ->bindParam(array(':username'=> $username));
    $stmt ->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result > 0){
        echo "このユーザーネームはすでに使われています。";
        exit; 
    }else {
        $sql = "INSERT INTO users(id, username, password) VALUES(NULL, :username, :password)";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":username", $_POST['username'], PDO::PARAM_STR);
        $stmt->bindParam(":password", $_POST['password'], PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if(!$result) {
        echo "書き込みに失敗しました。。";
        exit;
    }

}catch(PDOException $e) {
    echo "エラー!:" . str2html($e->getMessage());
}