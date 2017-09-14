<!doctype html>

<html>

<head>
  <meta charset="utf-8">
  <title>反馈与建议 - CANVAS</title>
  <link type="text/css" rel="stylesheet" href="index.css" media="screen">
</head>

<body>

  <?php
  session_start(); //建立会话

  if(isset($_SESSION["username"])) $username=$_SESSION["username"];

  ini_set("session.gc_maxlifetime",7*24*60*60); //设置会话超时
  ?>

  <nav>

    <div id="admin">
        <?php
        if(isset($_SESSION["username"])) {
          require_once "../mysql-login.php"; //登录mysql数据库
          $connect=new mysqli($hn,$un,$pw,$db); //连接数据库
          if($connect->connect_error) die($connect->connect_error."<br>"); //显示错误信息

          $query_profile="SELECT profile_photo_name FROM user WHERE username='$username'";
          $result_profile=$connect->query($query_profile);
          $row_profile=$result_profile->fetch_array(MYSQLI_NUM);
          $profile_photo_name=$row_profile[0];
          $profile_photo_path="../user/profile_photo/$profile_photo_name";

          echo <<<EOD
          <ul id="logined">
          <li><a id="log-in-button" href="../user/profile.php" target="_blank"><img src="$profile_photo_path"></a></li>
          <li><a id="log-in-button" href="../user/profile.php" target="_blank">$username</a></li>
          </ul>
EOD;
        }
          else echo<<<EOD
          <ul id="unlogin">
          <li><a id="sign-up-button" href="../sign-up.php" target="_blank">注册</a></li>
          <li><a id="log-in-button" href="../log-in.php" target="_blank">登录</a></li>
          </ul>
EOD;
          ?>
        <!-- 注册&登录按钮 -->
      </div>

    <table>
      <tr>
        <td>
          <img src="../image/canvas_logo_w.png" alt="CANVAS-Logo" height="38">
        </td>
        <td><a href="../index.php" title="Home">首页</a></td>
        <td><a href="../create/index.php" title="Create">创作</a></td>
        <td><a href="index.php" title="About">关于</a></td>
      </tr>
      <!-- 网站logo&导航按钮 -->
    </table>
    <!-- 导航栏 -->
  </nav>

  <div id="all-content">

    <sidebar>
      <a href="index.php" title="网站简介">
        <p>
          网站简介
        </p>
      </a>
      <a href="feedback.php" title="反馈与建议">
        <p class="selected-item">
          反馈与建议
        </p>
      </a>
      <a href="privacy.php" title="隐私政策">
        <p>
          隐私政策
        </p>
      </a>
      <!-- 边栏（浮动元素） -->
    </sidebar>

    <div class="about-area">
      <p>
          目前 CANVAS 还处于测试阶段<br />如果你希望这个网站变的更好<br />欢迎提出 bug 反馈与改进建议<br />发送到 👉&nbsp;&nbsp;<a href="mailto:xplusxu@gmail.com" style="color:#546E7A; font-weight:bold;">xplusxu@gmail.com</a><br /><br />😉
      </p>
      <!-- 相应的文字内容 -->
    </div>

    <!-- 页面主体中的所有内容 -->
  </div>

  <footer class="absolute-footer">
    <img src="../image/canvas_logo_circle.png" height="50px" alt="CANVAS-Logo">
    <p>
      Copyright &copy; <?php echo Date("Y"); ?> PlusXu<br>
      All rights reserved.
    </p>
    <!-- 页脚 -->
  </footer>

</body>

</html>
