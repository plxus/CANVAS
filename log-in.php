<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>登录 CANVAS</title>
  <link type="text/css" rel="stylesheet" href="log-in.css" media="screen">

  <script type="text/javascript">
  </script>
</head>

<body>

  <!-- 背景图片 -->
  <img src="image/login_bg.jpg" id="bgimg">

  <nav>

    <table>
      <tr>
        <td>
          <img src="image/canvas_logo_w.png" alt="CANVAS-Logo" height="38px">
        </td>
        <td><a href="index.php" title="Home">首页</a></td>
        <td><a href="create/index.php" title="Create">创作</a></td>
        <td><a href="about/index.php" title="About">关于</a></td>
      </tr>
      <!-- 网站logo&导航按钮 -->
    </table>

    <!-- 导航栏 -->
  </nav>

<div id="all-content">

  <form name="log_in" id="log_in" action="log-in.php" method="post">

    <h1>登录 CANVAS</h1>

    <div id="log_in_table">

      <div class="log_in_row">
        <p>用户名：</p>
        <p><input type="text" class="input_box" name="username" id="username" value="" size="30" required><br>
          <!-- <span class="post_prompt">
          请输入 30 个字符以内。
        </span> -->
      </p>
    </div>

    <div class="log_in_row">
      <p>密码：</p>
      <p><input type="password" class="input_box" name="password" id="password" value="" size="30" required><br>
        <!-- <span class="post_prompt">
        请输入 20 个字符以内。
      </span> -->
    </p>
  </div>

  <div class="log_in_row">
    <p></p>
    <p class="submit_button"><input type="submit" class="submit_button" name="submit" value="登录"></p>
  </div>

  <!-- 表单中的表格布局 -->
</div>

<?php
require_once "mysql-login.php"; //登录mysql数据库
$connect=new mysqli($hn,$un,$pw,$db); //连接数据库
if($connect->connect_error) die($connect->connect_error); //显示错误信息

function get_post($connect,$var){
  $temp=mysqli_real_escape_string($connect,$_POST[$var]);
  $temp=str_replace(' ','',$temp);
  $temp=strip_tags($temp);
  $temp=htmlentities($temp);
  return $temp;
}

if(isset($_POST["username"])&&isset($_POST["password"])){
  $username=get_post($connect,"username");
  $password=get_post($connect,"password");

  $query="SELECT username,password FROM user WHERE username='$username'"; //查询语句
  $result=$connect->query($query); //查询结果

  if(!$result) echo <<<EOD
    <p class="notice_fail">登录失败<br>$connect->error</p>
EOD;

  else if($result->num_rows){
    $row=$result->fetch_array(MYSQLI_NUM); //使用数组 $row 保存一行记录

    if($password==$row[1]){ //通过身份验证
      session_start(); //建立会话
      $_SESSION["username"]=$username;
      $_SESSION["password"]=$password;

      echo <<<EOD
        <p class="notice_ok">Hi $username ，欢迎登录 👏</p>
EOD;
      echo <<<EOD
        <p>即将跳转到 CANVAS 首页…… 🏃</p>
        <script type="text/javascript">
          setTimeout(window.location.href='index.php',5000);
        </script>
EOD;
    }
    else echo <<<EOD
      <p class="notice_fail">用户名或密码输入错误！</p>
EOD;

  }
  else echo <<<EOD
    <p class="notice_fail">用户名或密码输入错误！</p>
EOD;

  $connect->close(); //关闭数据库连接
}
?>

<p>
  <span id="link_signup">
    <a href="sign-up.php">还没有账号？立即注册</a>
  </span>
  <span id="link_privacy">
    <a href="about/privacy.php" title="隐私政策" target="_blank">隐私政策</a>
  </span>
</p>

<!-- 登录表单 -->
</form>

<!-- 页面主体的所有内容 -->
</div>

<footer>
  <img src="image/canvas_logo_circle.png" height="50px" alt="CANVAS">
  <p>
    Copyright &copy; <?php echo Date("Y"); ?> PlusXu<br>
    All rights reserved.
  </p>
  <!-- 页脚 -->
</footer>

</body>

</html>
