<?php
$to="s.nechaev@t-stark.ru, quicklybox@gmail.com, a.lugovtsev@t-stark.ru";
$sender = "export@q-box.ru";

$theme  = "Заявка с Q-Box Export Lp";

//вторая валидация на сервере
$regName = "/[^а-яЁё\s]+/ui";
$regMail = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/";
$regPhone = "/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/";
$flagError = false;
if (isset($_POST['mail'])) 
	{
		$mail  = trim(strip_tags($_POST['mail']));
		if (!preg_match($regMail, $mail)) $flagError = true;
	}
if (isset($_POST['name'])) 
	{
		$name  = trim(strip_tags($_POST['name']));
		if (preg_match($regName, $name)) $flagError = true;
	}
if (isset($_POST['phone'])) 
	{
		$phone = trim(strip_tags($_POST['phone']));
		if (!preg_match($regPhone, $phone)) $flagError = true;
	}
if (isset($_POST['fromwhatpage'])) $fromwhatpage = trim(strip_tags($_POST['fromwhatpage']));
$country = '';
if (isset($_POST['country']) && $_POST['country']=='' && isset($_POST['isgeo'])) {
    $country = 'Россия';
} elseif (isset($_POST['isgeo'])) {
    $country = trim(strip_tags($_POST['country']));
    if (preg_match($regName, $country)) $flagError = true;
}

// если не пройдена валидация - логи, письмо, прекращение выполнения
if ($flagError) {
	$datastr = "Имя: ".$_POST['name'].";\r\nТелефон: ".$_POST['phone'].";\r\nEmail: ".$_POST['mail'].";\r\nСтрана: ".$_POST['country']
						.";\r\nIP: ".$_SERVER['REMOTE_ADDR'].";\r\nСервер: ".$_SERVER['SERVER_SIGNATURE'].";\r\nС какой страницы отправлена форма: "
						.$_SERVER['HTTP_REFERER'].";\r\nОтработавший скрипт: ".$_SERVER['REQUEST_URI'].";";
	$datastrformail = "Имя: ".$_POST['name'].";\r\nТелефон: ".$_POST['phone'].";\r\nEmail: ".$_POST['mail'].";\r\nСтрана: ".$_POST['country'].";\r\nСмотреть логи";			
	file_put_contents("logs/".date('d-m-Y G:i:s').".txt", $datastr);
	mail("a.lugovtsev@t-stark.ru","Попытка отправить некорректные данные", $datastrformail, "From: q-box", "a.lugovtsev@t-stark.ru");
	header("Location: /");
	exit;
}

//формирование сообщения
$emailmess = "<table>";

if(!empty($fromwhatpage)){
$emailmess .= "<tr><td style='border:1px solid;padding:6px 6px 6px 6px;'>Отправлено со страницы: </td><td style='border:1px solid;padding:6px 6px 6px 6px;'>".$fromwhatpage."</td></tr>";
}
if(!empty($name)){
$emailmess .= "<tr><td style='border:1px solid;padding:6px 6px 6px 6px;'>Имя: </td><td style='border:1px solid;padding:6px 6px 6px 6px;'>".$name."</td></tr>";
}
if(!empty($phone)){
$emailmess .= "<tr><td style='border:1px solid;padding:6px 6px 6px 6px;'>Телефон: </td><td style='border:1px solid;padding:6px 6px 6px 6px;'>".$phone."</td></tr>";
}
if(!empty($mail)){
$emailmess .= "<tr><td style='border:1px solid;padding:6px 6px 6px 6px;'>e-mail: </td><td style='border:1px solid;padding:6px 6px 6px 6px;'>".$mail."</td></tr>";
}
if(!empty($country)){
$emailmess .= "<tr><td style='border:1px solid;padding:6px 6px 6px 6px;'>Страна: </td><td style='border:1px solid;padding:6px 6px 6px 6px;'>".$country."</td></tr>";
}
$emailmess .= "</table>";


$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";

$headers .= "From: Export <".$sender.">\r\n";

// TODO: Настроить postfix с дефолтным отправителем
if (isset($_POST['name']) || isset($_POST['phone'])) {
mail($to,$theme, $emailmess, $headers, $sender);
} else {
	header("Location: /");
}
