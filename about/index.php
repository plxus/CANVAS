<!doctype html>

<html>

<head>
  <meta charset="utf-8">
  <title>关于 - CANVAS</title>
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
        <p class="selected-item">
          网站简介
        </p>
      </a>
      <a href="feedback.php" title="反馈与建议">
        <p>
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
        CANVAS，一个关于设计的平台。
      </p>
      <p>
        建立这个网站的意义在于，<br />让每个人都能自由地探索设计灵感与创意。
      </p>
      <p>
        致力于汇集行业内优秀的设计师、插画师、摄影师、艺术家和策展人,<br />同时面向所有对设计感兴趣，<br />愿意分享设计灵感与创意的大众群体，<br />共同构建出一个设计流行交互平台，<br />打造以原创为核心的“设计生态圈”。
      </p>
      <p style="color:#9E9E9E;">
        您现在看到的是 CANVAS Demo 版，<br>其中使用了一些来自站酷ZCOOL平台的作品，<br>该部分内容的版权归原作者所有。
      </p>
      <p style="color:#9E9E9E;">
        本网站使用了一些来自 Unsplash 的图片。
      </p>
      <p>
        <br><br>
        <img src="../image/canvas_logo_wg.png" alt="CANVAS Logo"><br>
        <b>CANVAS</b>&nbsp;&nbsp;v0.9 beta<br>
        <b>Designed by PlusXu</b>
      </p>
      <!-- 相应的文字内容 -->
    </div>

    <!-- 页面主体中的所有内容 -->
  </div>

  <footer>
    <img src="../image/canvas_logo_circle.png" height="50px" alt="CANVAS-Logo">
    <p>
      Copyright &copy; <?php echo Date("Y"); ?> PlusXu<br>
      All rights reserved.
    </p>
    <!-- 页脚 -->
  </footer>

</body>

</html>
