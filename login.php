<?php
session_start();
/** @var mysqli $db */
require __DIR__ . '/db.php';
$db = db();
if ($_POST) {
    $username = addslashes($_POST['username']);
    $password = $_POST['password'];
    $user = $db->query("select * from users where username='{$username}'")->fetch_assoc();
    if ($user['password'] == md5($password)) {
        unset($user['password']);
        $_SESSION['user'] = $user;
        header( "refresh:2;url=/" );
        echo 'login success';
    } else {
        header( "refresh:2;url=login.php" );
        echo 'login failed';
    }
    return;
}
?>
<!doctype html>
<html
    lang="en">
<head>
    <meta
        charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta
        http-equiv="X-UA-Compatible"
        content="ie=edge">
    <title>
        登录</title>
</head>
<body>
<form method="post"
    action="">
    <table>
        <tr>
            <td width="150" align="right">
                用户名：
            </td>
            <td>
                <input
                    type="text" name="username" />
            </td>
        </tr>
        <tr>
            <td align="right">
                密码：
            </td>
            <td>
                <input type="password" name="password" />
            </td>
        </tr>
        <tr>
            <td align="right"></td>
            <td>
                <input
                    type="submit" value="提交"/>
            </td>
        </tr>
    </table>


</form>
</body>
</html>
