<?php
  $username=$_GET['username'];
  $id=$_GET['id'];

  require_once "../mysql-login.php"; //登录mysql数据库
  $connect=new mysqli($hn,$un,$pw,$db); //连接数据库
  $query="INSERT INTO star(username,id) VALUES('$username',$id)";
  $result=$connect->query($query);

  if(!$result) die("更新点赞数错误！");
