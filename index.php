<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$login      = 'leliaoff'; //ok: +79608555555
$password   = '12345678';
$code       = '666';

require_once "Auth.php";

$auth = new Auth();

try
{
    /**
     * Тут будет либо ключ сессии, либо sms, что обозначает, что надо запросить смс-код у пользователя
     */
    $loginResult = $auth->login($login, $password);

    if($loginResult == 'incorrect') {
        exit('Неверный пароль');
    }
    
    echo 'Успешный вход<br/>';
    if($loginResult == 'sms') {
        
        echo 'Требуется подтверждение по СМС<br/>';
        $smsResult = $auth->confirmSms($code);
        if($smsResult == 'code incorrect') {
            exit('Неверный код СМС');
        }

        echo 'Успешное подтверждение<br/>';
        $key = $smsResult;

    } else {
        $key = $loginResult;
    }

    echo 'Авторизация закончена, ваш ключ: ' . $key;

}
catch(Exception $exception)
{
    echo($exception->getMessage());
}

/**
 * Вектор изменения:
 * - капча
 * - получение данных пользователя
 * - разлогинивание
 */