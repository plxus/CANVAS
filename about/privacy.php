<!doctype html>

<html>

<head>
  <meta charset="utf-8">
  <title>隐私政策 - CANVAS</title>
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
        <p>
          反馈与建议
        </p>
      </a>
      <a href="privacy.php" title="隐私政策">
        <p class="selected-item">
          隐私政策
        </p>
      </a>
      <!-- 边栏（浮动元素） -->
    </sidebar>

    <div class="about-area privacy-policy">
      <p>
        我们重视用户的隐私。您在使用我们的服务时，我们可能会收集和使用您的相关信息。我们希望通过本《隐私政策》向您说明，在使用我们的服务时，我们如何收集、使用、储存和分享这些信息，以及我们为您提供的访问、更新、控制和保护这些信息的方式。本《隐私政策》与您所使用的服务息息相关，希望您仔细阅读，在需要时，按照本《隐私政策》的指引，作出您认为适当的选择。本《隐私政策》中涉及的相关技术词汇，我们尽量以简明扼要的表述，并提供进一步说明的链接，以便您的理解。
      </p>
      <h3>我们可能收集的信息</h3>
      <p>
        我们提供服务时，可能会收集、储存和使用下列与您有关的信息。如果您不提供相关信息，可能无法注册成为我们的用户或无法享受我们提供的某些服务，或者无法达到相关服务拟达到的效果。
      </p>
      <h4>您提供的信息</h4>
      <p>您在注册账户或使用我们的服务时，向我们提供的相关个人信息，例如电话号码、电子邮件或银行卡号等；您通过我们的服务向其他方提供的共享信息，以及您使用我们的服务时所储存的信息。</p>
      <h4>其他方分享的您的信息</h4>
      <p>其他方使用我们的服务时所提供有关您的共享信息。</p>
      <h4>我们获取的您的信息</h4>
      <p>您使用服务时我们可能收集如下信息：</p>
      <p>日志信息，指您使用我们的服务时，系统可能通过cookies、web beacon或其他方式自动采集的技术信息，包括：</p>
      <ul>
        <li>设备或软件信息，例如您的移动设备、网页浏览器或用于接入我们服务的其他程序所提供的配置信息、您的IP地址和移动设备所用的版本和设备识别码；</li>
        <li>在使用我们服务时搜索或浏览的信息，例如您使用的网页搜索词语、访问的社交媒体页url地址，以及您在使用我们服务时浏览或要求提供的其他信息和内容详情；</li>
        <li>有关您曾使用的移动应用（APP）和其他软件的信息，以及您曾经使用该等移动应用和软件的信息；</li>
        <li>您通过我们的服务进行通讯的信息，例如曾通讯的账号，以及通讯时间、数据和时长；</li>
        </ul>
        <p>位置信息，指您开启设备定位功能并使用我们基于位置提供的相关服务时，收集的有关您位置的信息，包括：</p>
        <ul><li>您通过具有定位功能的移动设备使用我们的服务时，通过GPS或WiFi等方式收集的您的地理位置信息；</li>
          <li>您或其他用户提供的包含您所处地理位置的实时信息，例如您提供的账户信息中包含的您所在地区信息，您或其他人上传的显示您当前或曾经所处地理位置的共享信息，您或其他人共享的照片包含的地理标记信息；</li>
          <li>您可以通过关闭定位功能，停止对您的地理位置信息的收集。</li>
        </ul>
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
