<?php
/**
 * Created by PhpStorm.
 * User: Dmitry
 * Date: 19.04.2017
 * Time: 11:28
 */
include_once "mysql.php";
$id =$_GET['id']??'1';
$sql="select id,fromFIELD,toFIELD,subject,dateFIELD,open_count ,click_count from mail where id>";
$sql2="select count(*) as count from mail where id>";
$sql=$sql.intval($id);
$sql2=$sql2.intval($id);

$flag=false;

if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if (!$result = $mysqli->query($sql2)) {
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
if ($actor['count']==0){
    echo '';
    exit();
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

$arr=array();
while ($actor = $result->fetch_assoc()) {
    $arr[]=$actor;
}

$result->free();
//print_r($arr);
if (isset($_GET['id'])){
    echo (json_encode($arr,JSON_FORCE_OBJECT ));
}

//echo '<br><br>';
//
//$data = array();
//$data['fruits'] = array('id'=>'apple','aa'=>'banana');
//$data['animals'] = array('qe'=>'dog');
//print_r($data);
//echo json_encode($data);