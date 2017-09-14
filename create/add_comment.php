<?php
$username=$_GET['username'];
$id=$_GET['id'];
$comment=$_GET['comment'];

require_once "../mysql-login.php"; //登录mysql数据库
$connect=new mysqli($hn,$un,$pw,$db); //连接数据库

function get_post($connect,$var){
  $temp=mysqli_real_escape_string($connect,$var);
  $temp=str_replace(PHP_EOL,'',$temp);
  $temp=strip_tags($temp);
  $temp=htmlentities($temp);
  return $temp;
}

$comment=get_post($connect,$comment);

//添加评论到数据库
$query_addcom="INSERT INTO comment(username,id,content) VALUES('$username',$id,'$comment')";
$result_addcom=$connect->query($query_addcom);
if(!$result_addcom) die("添加评论失败：".$connect->error."<br>");
