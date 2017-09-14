<!doctype html>

<html>

<head>
  <meta charset="utf-8">
  <title>创作 - CANVAS</title>
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
        <td><a href="index.php" title="Create">创作</a></td>
        <!-- <td><a href="../article/index.php" title="Article">文章</a></td> -->
        <td><a href="../about/index.php" title="About">关于</a></td>
      </tr>
      <!-- 网站logo&导航按钮 -->
    </table>
    <!-- 导航栏 -->
  </nav>

  <div id="all-content">

    <div id="box">
      <?php
      if(isset($username)) echo <<<EOD
        <a href="../post_work.php" target="_blank" title="发布作品">
        <button type="button" class="logined">发布作品</button>
        </a>
        <!-- 发布作品 -->

        <a href="../post_article.php" target="_blank" title="发布文章">
        <button type="button" class="logined">发布文章</button>
        </a>
        <!-- 发布文章 -->
EOD;
      else echo <<<EOD
        <button class="unlogin">发布作品</button>
        <button class="unlogin">发布文章</button>
EOD;
      ?>
    </div>

    <div id='work_flow'>

      <?php
        if(!isset($username)) echo '<p class="notice">要发布作品和文章，请先登录 CANVAS。</p>';
      ?>

      <?php
      require_once "../mysql-login.php"; //登录mysql数据库
      $connect=new mysqli($hn,$un,$pw,$db); //连接数据库
      if($connect->connect_error) die($connect->connect_error); //显示错误信息

      $query1="SELECT * FROM work_article ORDER BY post_datetime DESC";
      $result=$connect->query($query1);

      if(!$result) die("访问数据库失败：".$connect->error."<br>");

      $rows=$result->num_rows; //查询结果的行数（记录数）

      for($i=0;$i<$rows;$i++){
        $result->data_seek($i);
        $row=$result->fetch_array(MYSQLI_NUM); //保存查询结果的每行记录的数组

        $id=$row[7]; //作品id

        $query2="SELECT count(*) FROM star WHERE id=$id"; //查询点赞数
        $star_num_result=$connect->query($query2); //点赞数查询结果
        $star_num_row=$star_num_result->fetch_array(MYSQLI_NUM); //一行记录
        $star_num=$star_num_row[0]; //提取点赞数

        if($row[3]=="作品") {
          $page_path="work/$id.php";
          // if(!file_exists($page_path)){
          $fh=fopen($page_path,'w') or die("无法创建文件 $page_path"); //创建文件并打开

          copy('page_source.php',$page_path) or die("无法写入文件 $page_path"); //写入网页(复制)
          fclose($fh); //关闭文件
            // }
          $cover_path="work/$row[8]"; //封面图路径
        }

        if($row[3]=="文章") {
          $page_path="article/$id.php";
          // if(!file_exists($page_path)){
          $fh=fopen($page_path,'w') or die("无法创建文件 $page_path"); //创建文件并打开

          copy('page_source.php',$page_path) or die("无法写入文件 $page_path"); //写入网页(复制)
          fclose($fh); //关闭文件
          // }
          $cover_path="article/$row[8]"; //封面图路径
        }

        echo <<<EOD
        <div class="work_flow_row">
        <div class="work_flow_cell">

        <div class="work_cell_cover">
        <a href="$page_path?id=$id" target="_blank">
        <img src="$cover_path" alt="创作封面">
        </a>
        </div>

        <div class="work_cell_content">

        <a href="$page_path?id=$id" target="_blank">
        <div class="work_cell_title">
        $row[0]
        <!-- 作品标题 -->
        </div>

        <div class="work_cell_by">
        $row[1]
        <!-- 作者（用户名）-->
        </div>

        <div class="work_cell_intro">
        $row[2]
        <!-- 简介 -->
        </div>
        </a>

        <div class="work_cell_about">
        <span>$row[3] / $row[4]</span><br> <!-- 类别 -->
        <span>$row[5]</span> <!-- 发布日期 -->
        <span>$star_num 赞</span> <!-- 点赞数 -->
        <!-- 附加信息 -->
        </div>

        <!-- 信息块文本 -->
        </div>
        <!-- 作品信息块（单元格） -->
        </div>
        <!-- 作品信息流（表行） -->
        </div>
EOD;
//顶格写！！
      }
      ?>

      <!-- 作品信息流（CSS 表格布局） -->
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
