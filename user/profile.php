<!doctype html>

<html>

<head>
  <meta charset="utf-8">
  <title>用户信息 - CANVAS</title>
  <link type="text/css" rel="stylesheet" href="profile.css" media="screen">

  <script src="../jquery-3.2.0.min.js"></script>
  <script>
  function exit_confirm(){
    if(confirm("你确定要退出登录吗？")){
      // setTimeout(window.location.href='destroy_session.php',3000);
      $.ajax({
        type:"GET",
        url:"destroy_session.php",
      });
      setTimeout(window.location.href='../index.php',1000);
    }
  }
  </script>
</head>

<body>

  <?php
    session_start(); //建立会话

    if(isset($_SESSION["username"])){
    $username=$_SESSION["username"];
    $password=$_SESSION["password"];
    }

    ini_set("session.gc_maxlifetime",7*24*60*60); //设置会话超时
  ?>

  <nav>

    <table>
      <tr>
        <td>
          <img src="../image/canvas_logo_w.png" alt="CANVAS-Logo" height="38">
        </td>
        <td><a href="../index.php" title="Home">首页</a></td>
        <td><a href="../create/index.php" title="Create">创作</a></td>
        <!-- <td><a href="../article/index.php" title="Article">文章</a></td> -->
        <td><a href="../about/index.php" title="About">关于</a></td>
      </tr>
      <!-- 网站logo&导航按钮 -->
    </table>
    <!-- 导航栏 -->
  </nav>

  <div id="all-content">

    <div id="box">
        <button type="button" onclick="exit_confirm();">退出登录</button>
      <!-- 发布作品 -->
    </div>

    <div id="user_profile">

      <h1>个人信息</h1>

      <div id="user_info">

        <?php
        require_once "../mysql-login.php"; //登录mysql数据库
        $connect=new mysqli($hn,$un,$pw,$db); //连接数据库
        if($connect->connect_error) die($connect->connect_error."<br>"); //显示错误信息

        $query_profile="SELECT profile_photo_name FROM user WHERE username='$username'";
        $result_profile=$connect->query($query_profile);
        $row_profile=$result_profile->fetch_array(MYSQLI_NUM);
        $profile_photo_name=$row_profile[0];
        $profile_photo_path="profile_photo/$profile_photo_name";

        echo <<<EOD
          <img id="user_photo" src="$profile_photo_path">
EOD;
        ?>

        <p>
          <form name="change_info" id="change_info" action="profile.php" method="post">
            <table>
              <tr>
                <td>用户名：</td>
                <td><input type="text" class="input_box" size="40" name="username_new" value="<?php echo $username;?>"/></td>
              </tr>
              <tr>
                <td>密码：</td>
                <td><input type="password" class="input_box" size="40" name="password_new" value=""/></td>
              </tr>
              <tr>
              <td></td><td style="text-align:right;"><input type="submit" name="save_change" id="save_change" value="保存更改"></td>
              </tr>
            </table>
          </form>
        </p>

        <!-- 用户个人信息 -->
      </div>

    <div>
      <?php
      require_once "../mysql-login.php"; //登录mysql数据库
      $connect=new mysqli($hn,$un,$pw,$db); //连接数据库
      if($connect->connect_error) die($connect->connect_error); //显示错误信息

      function get_post($connect,$var){
        $temp=mysqli_real_escape_string($connect,$_POST[$var]);
        $temp=str_replace(PHP_EOL,'',$temp);
        $temp=strip_tags($temp);
        $temp=htmlentities($temp);
        return $temp;
      }

      if(isset($_POST["username_new"])&&isset($_POST["password_new"])){
        $username_new=get_post($connect,"username_new");
        $password_new=get_post($connect,"password_new");

        $query1="SELECT count(*) FROM user WHERE username='$username_new'"; //查询用户名是否被占用
        $num_result=$connect->query($query1);
        $num_row=$num_result->fetch_array(MYSQLI_NUM);
        $num=$num_row[0]; //新用户名在数据库中的记录数

        if($username_new==$username){ //用户名未更改

          if($password_new==$password) echo '<p class="notice_ok">未更改用户名和密码。</p>';

          else{
            $query2="UPDATE user SET password='$password_new' WHERE username='$username'";
            $result2=$connect->query($query2);

            if(!$result2) echo<<<EOD
              <p class="notice_fail">更改失败：<br> $query2<br>$connect->error</p>
EOD;
            else echo '<p class="notice_ok">更改成功！</p>';
            $_SESSION["password"]=$password_new; //更新会话
            $password=$_SESSION["password"];
          }
        }

        else{ //用户名更改

          if($num==1) echo<<<EOD
            <p class="notice_fail">该用户名已被占用，请尝试其他的用户名。</p>
EOD;
          else{
            $query3="UPDATE user SET username='$username_new',password='$password_new' WHERE username='$username'";
            $result3=$connect->query($query3);

            $query4="UPDATE work_article SET username='$username_new' WHERE username='$username'";
            $result4=$connect->query($query4);

            $query5="UPDATE star SET username='$username_new' WHERE username='$username'";
            $result5=$connect->query($query5);

            if(!$result3||!$result4||!$result5) echo<<<EOD
              <p class="notice_fail">更改失败：<br>$connect->error</p>
EOD;
            else {
              echo '<p class="notice_ok">更改成功，请重新登录。</p>';

              //更新头像文件名；

              session_start(); //建立会话
              $_SESSION["username"]=$username_new; //更新会话
              $username=$_SESSION["username"];
              $_SESSION["password"]=$password_new; //更新会话
              $password=$_SESSION["password"];
              require_once "destroy_session.php";
          }
        }
      }
    }
      ?>
    </div>

    <div>
      <h1 class="with_border">我的创作</h1>

      <table id="work_cell">

      <?php

      $query_work="SELECT title,category_large,category_small,post_date,id FROM work_article WHERE username='$username' ORDER BY post_datetime DESC";
      $result_work=$connect->query($query_work);

      if(!$result_work) die("访问数据库失败：".$connect->error."<br>");

      $rows=$result_work->num_rows; //查询结果的行数（记录数）

      if($rows==0) echo '<p style="color:#757575;">你还没有发布过作品和文章</p>';

      for($i=0;$i<$rows;$i++){
        $result_work->data_seek($i);
        $row=$result_work->fetch_array(MYSQLI_NUM); //保存查询结果的每行记录的数组

        $id=$row[4]; //作品id

        $query_star="SELECT count(*) FROM star WHERE id=$id"; //查询点赞数
        $star_num_result=$connect->query($query_star); //点赞数查询结果
        $star_num_row=$star_num_result->fetch_array(MYSQLI_NUM); //一行记录
        $star_num=$star_num_row[0]; //提取点赞数

        if($row[1]=="作品") {
          $page_path="../create/work/$id.php";
        }

        if($row[1]=="文章") {
          $page_path="../create/article/$id.php";
        }

        echo <<<EOD
            <tr class="work_cell_row">
              <td class="work_cell_title"><a href="$page_path?id=$id" class="link_page" target="_blank">$row[0]</a></td>
              <td class="work_cell_about" style="  color:#757575;font-weight: bold;">$row[1] / $row[2]</td>
              <td class="work_cell_about">$row[3]</td>
              <td class="work_cell_about work_cell_star">$star_num 赞</td>
            </tr>
EOD;

      }
      ?>
    </table>

    <h1 class="with_border">收藏列表</h1>

    <table id="work_cell">

    <?php

    $query_work="SELECT id FROM star WHERE username='$username' ORDER BY id DESC";
    $result_work=$connect->query($query_work);

    if(!$result_work) die("访问数据库失败：".$connect->error."<br>");

    $rows=$result_work->num_rows; //查询结果的行数（记录数）

    if($rows==0) echo '<p style="color:#757575;">你还没有收藏的作品和文章</p>';

    for($i=0;$i<$rows;$i++){
      $result_work->data_seek($i);
      $row=$result_work->fetch_array(MYSQLI_NUM); //保存查询结果的每行记录的数组

      $id_star=$row[0]; //作品id

      $query_star="SELECT count(*) FROM star WHERE id=$id_star"; //查询点赞数
      $star_num_result=$connect->query($query_star); //点赞数查询结果
      $star_num_row=$star_num_result->fetch_array(MYSQLI_NUM); //一行记录
      $star_num=$star_num_row[0]; //提取点赞数

      //查询点赞作品的详细信息
      $query_about="SELECT title,category_large,category_small,post_date FROM work_article WHERE id=$id_star";
      $result_about=$connect->query($query_about);
      $row_about=$result_about->fetch_array(MYSQLI_NUM); //保存查询结果的每行记录的数组

      if(!$result_about) die("访问数据库失败：".$connect->error."<br>");

      if($row_about[1]=="作品") {
        $page_path="../create/work/$id_star.php";
      }

      if($row_about[1]=="文章") {
        $page_path="../create/article/$id_star.php";
      }

      echo <<<EOD
          <tr class="work_cell_row">
            <td class="work_cell_title"><a href="$page_path?id=$id_star" class="link_page" target="_blank">$row_about[0]</a></td>
            <td class="work_cell_about" style="color:#757575;font-weight: bold;">$row_about[1] / $row_about[2]</td>
            <td class="work_cell_about">$row_about[3]</td>
            <td class="work_cell_about work_cell_star">$star_num 赞</td>
          </tr>
EOD;

    }
    $connect->close(); //关闭数据库连接

    ?>
  </table>

    </div>

      <!-- 作品信息流（CSS 表格布局） -->
    </div>

    <!-- 页面主体中的所有内容 -->
  </div>

  <footer>
    <img src="../image/canvas_logo_circle.png" height="50px" alt="CANVAS-Logo">
    <p>
      Copyright &copy; <?php echo date("Y"); ?> PlusXu<br>
      All rights reserved.
    </p>
    <!-- 页脚 -->
  </footer>

</body>

</html>
