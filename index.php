<?php
session_start();
/** @var mysqli $db */
require __DIR__ . '/db.php';
$user = $_SESSION['user'];
if (!$user) {
    header('refresh:0;url=login.php');
    return;
}
//写入主库
if ($_POST) {
    $writer = db(true);
    $title  = $writer->escape_string($_POST['title']);
    $body   = $writer->escape_string($_POST['body']);
    $res    = $writer->query("insert into posts (title, body) value ('{$title}', '{$body}')");
    header('refresh:0;url=/');
    return;
}

//读取从库
$db     = db();
$result = $db->query('select * from posts order by id desc limit 10');
$posts  = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">
    <title>
        数据写入</title>
</head>
<body>
<h2 style="text-align: right;">
    欢迎你，<?php echo $user['username']; ?></h2>
<ol>
    <?php foreach ($posts as $post) : ?>
        <li>
            <h3><?php echo $post['title']; ?></h3>
            <p>
                <?php echo $post['body']; ?>
            </p>
        </li>
    <?php endforeach; ?>
</ol>
<form method="post"
      action="">
    <table>
        <tr>
            <td width="80"
                align="right">
                标题：
            </td>
            <td>
                <input type="text"
                       name="title"/>
            </td>
        </tr>
        <tr>
            <td align="right">
                内容：
            </td>
            <td>
                <textarea
                        name="body"
                        cols="30"
                        rows="10"></textarea>
            </td>
        </tr>
        <tr>
            <td align="right"></td>
            <td>
                <input type="submit"
                       value="提交"/>
            </td>
        </tr>
    </table>
</form>
</body>
</html>
