<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>CANVAS - 自由地探索设计灵感与创意</title>
  <link type="text/css" rel="stylesheet" href="index.css" media="screen">

  <script type="text/javascript" src="jquery-3.2.0.min.js">
  </script>
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
            require_once "mysql-login.php"; //登录mysql数据库
            $connect=new mysqli($hn,$un,$pw,$db); //连接数据库
            if($connect->connect_error) die($connect->connect_error."<br>"); //显示错误信息

            $query_profile="SELECT profile_photo_name FROM user WHERE username='$username'";
            $result_profile=$connect->query($query_profile);
            $row_profile=$result_profile->fetch_array(MYSQLI_NUM);
            $profile_photo_name=$row_profile[0];
            $profile_photo_path="user/profile_photo/$profile_photo_name";

            echo <<<EOD
            <ul id="logined">
            <li><a id="log-in-button" href="user/profile.php" target="_blank"><img src="$profile_photo_path"></a></li>
            <li><a id="log-in-button" href="user/profile.php" target="_blank">$username</a></li>
            </ul>
EOD;
          }
          else echo <<<EOD
          <ul id="unlogin">
          <li><a id="sign-up-button" href="sign-up.php" target="_blank">注册</a></li>
          <li><a id="log-in-button" href="log-in.php">登录</a></li>
          </ul>
EOD;
          ?>
        <!-- 注册&登录按钮 -->
      </div>

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

        <div id="home-cover">
          <a href="about/index.php"><img src="image/home_banner.jpg" alt="CANVAS 自由地探索设计灵感与创意"></a>
          <!-- 首页 banner -->
        </div>

      <div id="home_flow">

        <?php
        require_once "mysql-login.php"; //登录mysql数据库
        $connect=new mysqli($hn,$un,$pw,$db); //连接数据库
        if($connect->connect_error) die($connect->connect_error."<br>"); //显示错误信息

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

          // if($row[3]=="作品") {
          //   $page_path="create/work/".$id.".php";
          //   $cover_path="create/work/$row[8]"; //封面图路径
          // }
          // if($row[3]=="文章") {
          //   $page_path="create/article/".$id.".php";
          //   $cover_path="create/article/$row[8]"; //封面图路径
          // }

          if($row[3]=="作品") {
            $page_path="create/work/$id.php";
            // if(!file_exists($page_path)){
            $fh=fopen($page_path,'w') or die("无法创建文件 $page_path"); //创建文件并打开

            copy('create/page_source.php',$page_path) or die("无法写入文件 $page_path"); //写入网页(复制)
            fclose($fh); //关闭文件
              // }
            $cover_path="create/work/$row[8]"; //封面图路径
          }

          if($row[3]=="文章") {
            $page_path="create/article/$id.php";
            // if(!file_exists($page_path)){
            $fh=fopen($page_path,'w') or die("无法创建文件 $page_path"); //创建文件并打开

            copy('create/page_source.php',$page_path) or die("无法写入文件 $page_path"); //写入网页(复制)
            fclose($fh); //关闭文件
            // }
            $cover_path="create/article/$row[8]"; //封面图路径
          }

          if($i%4==0) echo '<div class="home_flow_row">';

          echo <<<EOD
、          <div class="home_flow_cell">
            <div class="home_cell_cover">
              <a href="$page_path?id=$id" target="_blank">
                <img src="$cover_path" width="280px" height="210px">
              </a>
              <!-- 信息块封面 -->
            </div>

            <div class="home_cell_content">

              <div class="home_cell_title">
                <a href="$page_path?id=$id" target="_blank">$row[0]</a>
                <!-- 信息块标题 -->
              </div>

              <div class="home_cell_by">
              $row[1]
              <!-- 作者 -->
              </div>

              <div class="home_cell_about">
                <span>$row[3] / $row[4]</span><br>
                <span>$row[5]</span>
                <span>$star_num 赞</span>
                <!-- 附加信息 -->
              </div>

              <!-- 信息块文本 -->
            </div>

            <!-- 首页信息块（单元格） -->
          </div>
EOD;

          if($i%4==3||$i==$rows-1) echo '</div>';
        }
          ?>

        <!-- 首页信息流（CSS表格布局） -->
      </div>

      <!-- 页面主体中的所有内容 -->
    </div>

    <footer>
      <img src="image/canvas_logo_circle.png" height="50px" alt="CANVAS-Logo">
      <p>
        Copyright &copy; <?php echo date("Y"); ?> PlusXu<br>
        All rights reserved.
      </p>
      <!-- 页脚 -->
    </footer>

  </body>

  </html>
