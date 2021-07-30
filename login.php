<?php
session_start();
//XSS対策用関数およびデータベース接続処理用関数の読み込み
require_once __DIR__ . '/inc/functions.php';
include __DIR__ . '/inc/header.php';
?>
<form method='post' action='login.php' class='loginform'>
    <div style="text-align:center;">
        <b>ようこそ！ログインしてください</b>
    </div>
    <label for="username">ユーザー名:</label>
    <input type='text' name='username'>

    <label for="password">パスワード:</label>
    <input type='password' name='password'>
    
    <input type='submit' value='ログイン'>

    <label2><b>↓↓ユーザー登録がまだの方はこちら↓↓</b></label2>
    <input type='button' onclick="location.href='./registration.php'" value='ユーザー登録画面へ'>
</form>

<?php
if (!empty($_SESSION['login'])) {
    echo "ログイン済です<br>";
    echo "<a href=index.php>リストに戻る</a>";
    exit;
}
if ((empty($_POST['username'])) || (empty($_POST['password']))) {
    echo "<div style='text-align: center'>ユーザー名、パスワードを入力してください。</div>";
    exit;
}

try {
    $dbh = db_open(); //データベース接続処理関数
    $sql = "SELECT password FROM users WHERE username = :username";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":username", $_POST['username'], PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        echo "ログインに失敗しました。";
        exit;
    }

    if (password_verify($_POST['password'], $result['password'])) {
        session_regenerate_id(true);
        $_SESSION['login'] = true;
        header("Location: index.php");
    } else {
        echo 'ログインに失敗しました。(2)';
    }
} catch (PDOException $e) {
    echo "エラー!: " . str2html($e->getMessage());
    exit;
}

header("Location: index.php");  /*index.phpに自動的に遷移する */