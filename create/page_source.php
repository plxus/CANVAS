<!doctype html>

<html>

<head>
  <meta charset="utf-8">
  <title>作品与文章 - CANVAS</title>
  <link type="text/css" rel="stylesheet" href="../index.css" media="screen">
  <script type="text/javascript" src="../../jquery-3.2.0.min.js"></script>
  <script type="text/javascript">
  var is_star=int();
  function add_star(){
    if(is_star==0){
      dataval={username:$('#username_temp').val(),id:$('#id_temp').val()};
      $.ajax({
        type:"GET",
        url:"../add_star.php",
        data:dataval,
      });
      is_star=1;
      document.getElementById("star_button").setAttribute("class","star_1");
      document.getElementById("plus1").setAttribute("style","display:default;");
      return true;
    }
    if(is_star==1){
      dataval={username:$('#username_temp').val(),id:$('#id_temp').val()};
      $.ajax({
        type:"GET",
        url:"../remove_star.php",
        data:dataval,
      });
      is_star=0;
      document.getElementById("star_button").setAttribute("class","star_0");
      document.getElementById("plus1").setAttribute("style","display:none;");
      return true;
    }
  }

  function add_comment(){
    var issubmit=1;
    if($('#write_comment').val()=="") alert("请输入评论的内容！");
    else{
      dataset={username:$('#username_temp').val(),id:$('#id_temp').val(),comment:$('#write_comment').val()};
      $.ajax({
        type:"GET",
        url:"../add_comment.php",
        data:dataset,
      });
      if(issubmit==1) {
        document.getElementById("submit_fb").setAttribute("class","submit_ok");
        setTimeout(window.location.reload(),4000);
      }
    }
  }
  </script>
</head>

<body>

  <?php
  session_start(); //建立会话

  if(isset($_SESSION["username"])) $username=$_SESSION["username"];

  $id=$_GET['id']; //当前作品的id

  ini_set("session.gc_maxlifetime",7*24*60*60); //设置会话超时
  ?>

  <nav>
    <div id="admin">
        <?php
        if(isset($_SESSION["username"])) {
          require_once "../../mysql-login.php"; //登录mysql数据库
          $connect=new mysqli($hn,$un,$pw,$db); //连接数据库
          if($connect->connect_error) die($connect->connect_error."<br>"); //显示错误信息

          $query_profile="SELECT profile_photo_name FROM user WHERE username='$username'";
          $result_profile=$connect->query($query_profile);
          $row_profile=$result_profile->fetch_array(MYSQLI_NUM);
          $profile_photo_name=$row_profile[0];
          $profile_photo_path="../../user/profile_photo/$profile_photo_name";

          echo <<<EOD
          <ul id="logined">
          <li><a id="log-in-button" href="../../user/profile.php" target="_blank"><img src="$profile_photo_path"></a></li>
          <li><a id="log-in-button" href="../../user/profile.php" target="_blank">$username</a></li>
          </ul>
EOD;
        }
          else echo<<<EOD
          <ul id="unlogin">
          <li><a id="sign-up-button" href="../../sign-up.php" target="_blank">注册</a></li>
          <li><a id="log-in-button" href="../../log-in.php" target="_blank">登录</a></li>
          </ul>
EOD;
          ?>
      <!-- 注册&登录按钮 -->
    </div>

    <table>
      <tr>
        <td>
          <img src="../../image/canvas_logo_w.png" alt="CANVAS-Logo" height="38">
        </td>
        <td><a href="../../index.php" title="Home">首页</a></td>
        <td><a href="../index.php" title="Create">创作</a></td>
        <!-- <td><a href="../article/index.php" title="Article">文章</a></td> -->
        <td><a href="../../about/index.php" title="About">关于</a></td>
      </tr>
      <!-- 网站logo&导航按钮 -->
    </table>
    <!-- 导航栏 -->
  </nav>

  <div id="all-content">

    <?php
    require_once "../../mysql-login.php"; //登录mysql数据库
    $connect=new mysqli($hn,$un,$pw,$db); //连接数据库
    if($connect->connect_error) die($connect->connect_error); //显示错误信息

    $query="SELECT title,username,intro,category_large,category_small,post_date,cover_name FROM work_article WHERE id=$id";
    $result=$connect->query($query);

    if(!$result) echo <<<EOD
    <p class="notice_fail">访问数据库失败：<br>$connect->error</p>
EOD;

    if($result->num_rows){
      $row=$result->fetch_array(MYSQLI_NUM); //使用数组 $row 保存一行记录

      $query2="SELECT count(*) FROM star WHERE id=$id"; //查询点赞数
      $star_num_result=$connect->query($query2); //点赞数查询结果
      $star_num_row=$star_num_result->fetch_array(MYSQLI_NUM); //一行记录
      $star_num=$star_num_row[0]; //提取点赞数

      $foldername=substr($row[6],0,18); //保存图片内容的文件夹名

      if($row[3]=="作品"){
        $folder_dir="/Applications/XAMPP/xamppfiles/htdocs/canvas/create/work/$foldername/";
      }

      if($row[3]=="文章"){
        $folder_dir="/Applications/XAMPP/xamppfiles/htdocs/canvas/create/article/$foldername/";
      }

      if(isset($username)) echo <<<EOD
      <input type="hidden" name="username_temp" id="username_temp" value="$username" width="0px" height="0px" style="display:none;"/>
      <input type="hidden" name="id_temp" id="id_temp" value="$id" width="0px" height="0px" style="display:none;"/>
EOD;

    echo <<<EOD
    <div id="starbox">
      <p>
        <span>创作者：</span><br><span>$row[1]</span>
      </p>
EOD;

      if(isset($username)) { //已登录
        $query3="SELECT count(*) FROM star WHERE username='$username' AND id=$id";
        $result_isstar=$connect->query($query3);
        $result_isstar_row=$result_isstar->fetch_array(MYSQLI_NUM);
        $isstar=$result_isstar_row[0]; //已点赞为1，未点赞为0

        if($isstar==1) {
          $star_num=$star_num-1;
          echo <<<EOD
          <script>is_star=1;</script>
          <button type="button" id="star_button" onclick="add_star();" class="star_1">$star_num 赞&nbsp;&nbsp;<span id="plus1" style="display:default;">+1</span></button>
EOD;
        }
        else echo <<<EOD
          <script>is_star=0;</script>
          <button type="button" id="star_button" onclick="add_star();" class="star_0">$star_num 赞&nbsp;&nbsp;<span id="plus1" style="display:none;">+1</span></button>
EOD;
      }
      else //未登录
        echo <<<EOD
        <button type="button" class="star_unlogin">$star_num 赞</button>
EOD;

    echo <<<EOD
    </div>

    <div id="content">

        <h1>$row[0]</h1>
        <p id="about">
        分类在 <span>$row[3] / $row[4]</span>，发布于 $row[5]
        </p>
        <p id="intro">
        $row[2]
        </p>
        <p id="images">
EOD;
        $image_name_array=scandir($folder_dir);
        for($i=0;$i<count($image_name_array);$i++){
          if($image_name_array[$i]=="."||$image_name_array[$i]==".."||$image_name_array[$i]==".DS_Store") continue;
          echo <<<EOD
          <img src="$foldername/$image_name_array[$i]">
EOD;
          }
        echo <<<EOD
        </p>
        <h2>评论</h2>
EOD;
        if(isset($username)) echo <<<EOD
        <div id="post_comment">
        <textarea name="write_comment" id="write_comment" wrap="soft" rows="3" placeholder="在这里写下你的评论……"></textarea>
        <p>
        <span id="submit_fb" class="submit_none">提交成功！</span>
        <button type="button" id="submit_comment" onclick="add_comment();">发表</button>
        </p>
        </div>
EOD;
        else echo '<p class="notice">要发表评论，请先登录 CANVAS。</p>';

        //查询该作品的所有评论
        $query_comment="SELECT username,post_datetime,content FROM comment WHERE id=$id ORDER BY post_datetime";
        $result_comment=$connect->query($query_comment);
        if(!$result_comment) die("无法加载用户评论：".$connect->error."<br>");

        $rows=$result_comment->num_rows; //查询结果的行数（记录数）

        if($rows==0) echo '<p style="color:#9E9E9E; text-align:center; margin:40px 0 40px 0;">当前还没有用户评论</p>';

        else{
          echo '<div id="comment_area">';
          for($i=0;$i<$rows;$i++){
            $result_comment->data_seek($i);
            $row_comment=$result_comment->fetch_array(MYSQLI_NUM); //保存查询结果的每行记录的数组

            echo <<<EOD
            <div class="comment_item">
            <p class="comment_head">
            <span>$row_comment[0]</span><span>$row_comment[1]</span>
            </p>
            <p class="comment_content">
            $row_comment[2]
            </p>
            </div>
EOD;
          }
          echo '</div>';
        }
      }
      else echo <<<EOD
      <p class="notice_fail">访问数据库失败！</P>
EOD;

      ?>

      <!-- 作品内容 -->
    </div>

    <!-- 页面主体中的所有内容 -->
  </div>

  <footer>
    <img src="../../image/canvas_logo_circle.png" height="50px" alt="CANVAS-Logo">
    <p>
      Copyright &copy; <?php echo Date("Y"); ?> PlusXu<br>
      All rights reserved.
    </p>
    <!-- 页脚 -->
  </footer>

</body>

</html>
