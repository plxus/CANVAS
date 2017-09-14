<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>注册 CANVAS</title>
  <link type="text/css" rel="stylesheet" href="sign-up.css" media="screen">

  <script type="text/javascript">
  </script>
</head>

<body>

  <nav>

<table>
  <tr>
    <td>
      <img src="image/canvas_logo_w.png" alt="CANVAS-Logo" height="38">
    </td>
    <td><a href="index.php" title="Home">首页</a></td>
    <td><a href="create/index.php" title="Create">创作</a></td>
    <!-- <td><a href="article/index.php" title="Article">文章</a></td> -->
    <td><a href="about/index.php" title="About">关于</a></td>
  </tr>
  <!-- 网站logo&导航按钮 -->
</table>

<!-- 导航栏 -->
</nav>

<div id="all-content">

  <form name="sign_up" id="sign_up" action="sign-up.php" method="post" enctype="multipart/form-data">
    <h1>注册 CANVAS</h1>

    <div id="sign_up_table">

      <div class="sign_up_row">
        <p>用户名：</p>
        <p><input type="text" class="input_box" name="username" id="username" value="" size="40" required><br>
          <span class="post_prompt">
            不超过 30 个字符。
          </span>
        </p>
      </div>

      <div class="sign_up_row">
        <p>密码：</p>
        <p><input type="password" class="input_box" name="password" id="password" value="" size="40" required><br>
          <span class="post_prompt">
            不超过 20 个字符。
          </span>
        </p>
      </div>

      <div class="sign_up_row">
        <p>确认密码：</p>
        <p><input type="password" class="input_box" name="password_confirm" id="password_confirm" value="" size="40" required><br>
          <span class="post_prompt">
            请再次输入密码。
          </span>
        </p>
      </div>

      <div class="sign_up_row">
        <p>上传头像：</p>
        <p><input type="file" accept="image/jpg,image/jpeg,image/png" name="profile_photo" id="profile_photo" style="cursor: pointer;" required><br>
          <span class="post_prompt">
            支持上传 jpg, jpeg, png 格式的图片，<br>不超过 1MB。
          </span>
        </p>
      </div>

      <div class="sign_up_row">
        <p></p>
        <p><label><input type="checkbox" name="agree" value="agree" required>我同意用户使用协议</label></p>
      </div>

      <div class="sign_up_row">
        <p></p>
        <p class="submit_button"><input type="submit" class="submit_button" name="submit" value="注册" onclick="submit_done()"></p>
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

    if(isset($_POST["username"])&&isset($_POST["password"])&&isset($_POST["password_confirm"])){
      $username=get_post($connect,"username");
      $password=get_post($connect,"password");
      $password_confirm=get_post($connect,"password_confirm");

      if($password==$password_confirm){

      if(is_uploaded_file($_FILES["profile_photo"]["tmp_name"])&&$_FILES["profile_photo"]["size"]<1000000){
        if($_FILES["profile_photo"]["error"]) echo <<<EOD
          <p class="notice_fail">上传头像失败！</p>
EOD;
        else{
          $filename_old=$_FILES["profile_photo"]["name"]; //原文件名
          $filename_new=$username; //新文件名（无扩展名）
          $filetype=substr($filename_old,strrpos($filename_old,"."),strlen($filename_old)-strrpos($filename_old,".")); //文件扩展名（类型）
          $filename_new=$filename_new.$filetype; //加扩展名
          $profile_photo_name=$filename_new;

          $savedir="/Applications/XAMPP/xamppfiles/htdocs/canvas/user/profile_photo/".$profile_photo_name; //存储目录（绝对路径）

          if(move_uploaded_file($_FILES["profile_photo"]["tmp_name"],$savedir)){
            $query="INSERT into user values("."'$username','$password','$profile_photo_name')";
            $result=$connect->query($query);

            if(!$result) echo <<<EOD
              <p class="notice_fail">注册失败：<br>$connect->error</p>
EOD;

            else echo <<<EOD
              <p class="notice_ok">注册成功，即将跳转到登录页面……</p>
              <script type="text/javascript">
              setTimeout(window.location.href='log-in.php',4000);
              </script>
EOD;
          }
          else echo <<<EOD
            <p class="notice_fail">注册失败：无法上传头像，写入服务器错误。</p>
EOD;
        }
      }
    }
    else echo '<p class="notice_fail">注册失败：请确认密码填写正确。</p>';

      $connect->close(); //关闭数据库连接
    }
    ?>

    <p>
      <span id="link_login">
        <a href="log-in.php">已有账号？立即登录</a>
      </span>
      <span id="link_privacy">
        <a href="about/privacy.php" title="隐私政策" target="_blank">隐私政策</a>
      </span>
    </p>

    <!-- 注册表单 -->
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
