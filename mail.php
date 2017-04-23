<?php
/**
 * Created by PhpStorm.
 * User: Dmitry
 * Date: 19.04.2017
 * Time: 9:35
 */
include_once "mysql.php";
require 'vendor/autoload.php';
use Mailgun\Mailgun;


function test_input($data) {
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$mgClient = new Mailgun('key-bc7e9ad570642c7f16746fcbd6440ad6');
$domain = "sandbox8dbdaf36f4334c5d94b436ef81366317.mailgun.org";


list($from,$to,$subject,$body)=array_values($_POST);
$from=test_input($from);
$to=test_input($to);
$subject=test_input($subject);
$body=test_input($body);

# Make the call to the client.
$result = $mgClient->sendMessage("$domain",
    array('from'    => $from,
        'to'      => $to,
        'subject' => $subject,
        'text'    => $body));


if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

/* подготавливаемый запрос, первая стадия: подготовка */
if (!($stmt = $mysqli->prepare("INSERT INTO mail(subject,body,fromFIELD,toFIELD,dateFIELD) VALUES (?,?,?,?,?)"))) {
    echo "Не удалось подготовить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
}



if (!$stmt->bind_param("sssss", $subject,$body,$from,$to,date('Y-m-d'))) {
    echo "Не удалось привязать параметры: (" . $stmt->errno . ") " . $stmt->error;
}

if (!$stmt->execute()) {
    echo "Не удалось выполнить запрос: (" . $stmt->errno . ") " . $stmt->error;
}

$stmt->close();
