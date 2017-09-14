<!doctype html>

<html>

<head>
  <meta charset="utf-8">
  <title>发布文章 - CANVAS</title>
  <link type="text/css" rel="stylesheet" href="post_article.css" media="screen">

  <script type="text/javascript">
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
        else echo<<<EOD
        <ul id="unlogin">
        <li><a id="sign-up-button" href="sign-up.php" target="_blank">注册</a></li>
        <li><a id="log-in-button" href="log-in.php" target="_blank">登录</a></li>
        </ul>
EOD;
        ?>
      <!-- 注册&登录按钮 -->
    </div>

    <table>
      <tr>
        <td>
          <img src="image/canvas_logo_w.png" alt="CANVAS-Logo" height="38">
        </td>
        <td><a href="index.php" title="Home" target="_blank">首页</a></td>
        <td><a href="create/index.php" title="Create" target="_blank">创作</a></td>
        <td><a href="about/index.php" title="About" target="_blank">关于</a></td>
      </tr>
      <!-- 网站logo&导航按钮 -->
    </table>

    <!-- 导航栏 -->
  </nav>

  <div id="all-content">

    <div id="box">
      <?php
        if(isset($username)) echo '<input type="submit" form="post_article" name="submit_post" value="确认发布" onclick="submit_post()">';
        else echo '<button>确认发布</button>';
      ?>
    </div>

    <div id="post_edit">

      <h1>发布文章</h1>

      <?php
        if(!isset($username)) echo '<p class="notice_fail">您还未登录！</p>';
      ?>

      <form name="post_article" id="post_article" action="post_article.php" method="post" enctype="multipart/form-data">

        <div id="post_table">

          <div class="post_row">
            <div class="post_cell">
              文章标题：
            </div>
            <div class="post_cell">
              <input type="text" class="input_title" name="title" value="" size="50" required>
              <div class="post_prompt">
                不超过 50 个字符。
              </div>
            </div>
          </div>

          <div class="post_row">
            <div class="post_cell" style="vertical-align:top;">
              文章简介：
            </div>
            <div class="post_cell">
              <textarea name="intro" rows="6" cols="60" wrap="soft" required></textarea>
              <div class="post_prompt">
                不超过 500 个字符。
              </div>
            </div>
          </div>

          <div class="post_row">
            <div class="post_cell" style="vertical-align:top;">
              上传封面图：
            </div>
            <div class="post_cell">
              <input type="file" accept="image/jpg,image/jpeg,image/png" name="cover" style="cursor: pointer;" required>
              <div class="post_prompt">支持上传 jpg, jpeg, png 格式的图片，建议尺寸为 400×300px，不超过 1MB。</div>
            </div>
          </div>

          <div class="post_row">
            <div class="post_cell" style="vertical-align:top;">
              文章分类：
            </div>
            <div class="post_cell">
              <select name="category_small" size="1" style="width:150px;" required>
                <option value="">- 请选择 -</option>
                <option value="平面">平面</option>
                <option value="GUI">GUI</option>
                <option value="字体">字体</option>
                <option value="插画">插画</option>
                <option value="摄影">摄影</option>
                <option value="三维">三维</option>
                <option value="工业和产品">工业和产品</option>
                <option value="建筑">建筑</option>
                <option value="手工艺">手工艺</option>
              </select>
              <div class="post_prompt">
                请选择文章所属的类别。
              </div>
            </div>
          </div>

          <div class="post_row">
            <div class="post_cell" style="vertical-align:top;">
              上传文章内容：
            </div>
            <div class="post_cell">
              <input type="file" name="create_images[]" multiple="multiple" accept="image/jpg,image/jpeg,image/png,image/gif" style="cursor: pointer;" required>
              <div class="post_prompt">
                支持上传 jpg, jpeg, png, gif 格式的图片，每张图片不超过 5MB。
              </div>
            </div>
          </div>

          <!-- 表格布局 -->
        </div>

        <input type="hidden" name="category_large" value="文章">
        <input type="hidden" name="post_date" value="<?php date_default_timezone_set("Asia/Shanghai"); echo date("Y-m-d");?>">
        <input type="hidden" name="post_datetime" value="<?php date_default_timezone_set("Asia/Shanghai"); echo date("Y-m-d H:i:s");?>">
        <!-- <input type="hidden" name="username" value=""> -->
        <!-- 发布文章的表单 -->
      </form>

      <?php
      require_once "mysql-login.php"; //登录mysql数据库
      $connect=new mysqli($hn,$un,$pw,$db); //连接数据库
      if($connect->connect_error) die($connect->connect_error); //显示错误信息

      function get_post($connect,$var){
        $temp=mysqli_real_escape_string($connect,$_POST[$var]);
        $temp=str_replace(PHP_EOL,'',$temp);
        $temp=strip_tags($temp);
        $temp=htmlentities($temp);
        return $temp;
      }

      function get_post_intro($connect,$var){
        // $temp=mysqli_real_escape_string($connect,$_POST[$var]);
        $temp=$_POST[$var];
        $temp=strip_tags($temp);
        $temp=htmlentities($temp);
        $temp=nl2br($temp);
        return $temp;
      }

      if(isset($_POST["title"])&&isset($_POST["intro"])&&isset($_POST["category_small"])&&isset($_POST["category_large"])&&isset($_POST["post_date"])&&isset($_POST["post_datetime"])&&isset($username)){
        $title=get_post($connect,"title");
        $intro=get_post_intro($connect,"intro");
        $category_small=get_post($connect,"category_small");
        $category_large=get_post($connect,"category_large");
        $post_date=get_post($connect,"post_date");
        $post_datetime=get_post($connect,"post_datetime");

        $isok=1; //判断是否有错误
        //上传封面图
        if(is_uploaded_file($_FILES["cover"]["tmp_name"])){
          if($_FILES["cover"]["size"]<1000*1000){

          if($_FILES["cover"]["error"]) {
            $isok=0;
            echo <<<EOD
              <p class="notice_fail">上传封面图失败！</p>
EOD;
          }
          else{
            $filename_old=$_FILES["cover"]["name"]; //原文件名
            $filename_new=date("YmdHis"); //新文件名（无扩展名）
            $rand=rand(0,9999);
            $filename_new=$filename_new.$rand; //加入随机数后缀

            $imagename_pre=$filename_new; //文章内容图像的文件夹名（前导）

            $filetype=substr($filename_old,strrpos($filename_old,"."),strlen($filename_old)-strrpos($filename_old,".")); //文件扩展名（类型）
            $filename_new=$filename_new.$filetype; //加扩展名
            $cover_name=$filename_new; //保存为封面文件名

            $savedir="/Applications/XAMPP/xamppfiles/htdocs/canvas/create/article/".$cover_name; //保存目录

            if(!move_uploaded_file($_FILES["cover"]["tmp_name"],$savedir)){
                $isok=0;
                echo <<<EOD
                  <p class="notice_fail">上传封面图失败：无法将文件写入服务器。</p>
EOD;
              }

            //上传文章内容
            $create_images=$_FILES['create_images'];
            $i=0;
            foreach($_FILES['create_images']['name'] as $name){
              if($_FILES['create_images']['size'][$i]<5000*1000){
                $savedir_temp="/Applications/XAMPP/xamppfiles/htdocs/canvas/create/article/$imagename_pre/"; //图片保存的文件夹
                if(!is_dir($savedir_temp)) mkdir($savedir_temp);

                $filename_old=$_FILES['create_images']['name'][$i];
                $filetype=substr($filename_old,strrpos($filename_old,"."),strlen($filename_old)-strrpos($filename_old,".")); //文件扩展名（类型）

                $images_name=$i+1;
                $images_name=$images_name.$filetype; //每个图像文件名
                $savedir_images=$savedir_temp.$images_name; //文章内容的保存目录

                if(!move_uploaded_file($_FILES["create_images"]["tmp_name"][$i],$savedir_images)){
                    $isok=0;
                    echo <<<EOD
                      <p class="notice_fail">上传文章内容失败：无法将文件写入服务器。</p>
EOD;
                  }
                $i=$i+1;
              }
              else {
                $isok=0;
                echo <<<EOD
                <p class="notice_fail">请上传有效的文章内容！</p>
EOD;
}
            }
          }
        }
        else {
          $isok=0;
          echo <<<EOD
          <p class="notice_fail">请上传有效的封面图！</p>
EOD;
}
}

        if($isok==1){
          $query="INSERT INTO work_article(title,username,intro,category_large,category_small,post_date,post_datetime,cover_name) VALUES"."('$title','$username','$intro','$category_large','$category_small','$post_date','$post_datetime','$cover_name')";
          $result=$connect->query($query);

          if(!$result) echo <<<EOD
            <p class="notice_fail">发布失败： <br>$query<br>$connect->error</p>
EOD;
          else echo '<p class="notice_ok">发布成功！</p>';
        }
        else echo<<<EOD
        <p class="notice_fail">文章发布失败。</p>
EOD;

        // $result->close(); //关闭查询结果
        $connect->close(); //关闭数据库连接
      }
      ?>

      <!-- 发布编辑区 -->
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
