<?php
/**
 * Created by PhpStorm.
 * User: Dmitry
 * Date: 19.04.2017
 * Time: 11:28
 */
include_once "mysql.php";
$id =$_GET['id']??'1';
$sql="select * from mail where id=";
$sql2="update mail set ";
$sql=$sql.intval($id);

if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if (!$result = $mysqli->query($sql)) {
    // О нет! запрос не удался.
    echo "Извините, возникла проблема в работе сайта.";
    // И снова: не делайте этого на реальном сайте, но в этом примере мы покажем,
    // как получить информацию об ошибке:
    echo "Ошибка: Наш запрос не удался и вот почему: \n";
    echo "Запрос: " . $sql . "\n";
    echo "Номер_ошибки: " . $mysqli->errno . "\n";
    echo "Ошибка: " . $mysqli->error . "\n";
    exit;
}

$actor = $result->fetch_assoc();

if (is_null($actor)){
    echo '';
    exit();
}else{
    $openCount=$actor['open_count'];
    $clickCount=$actor['click_count'];
    $sql2=$sql2.' open_count='.($openCount+1).', click_count='.($clickCount+1).' where id='.intval($id);
    $mysqli->query($sql2);
    //echo $sql2;
    //$mysqli->execute();
    echo json_encode(array('subject'=>$actor['subject'],'body'=>$actor['body']));
}
