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
    
    if($loginResult == 'sms') {
        $key = $auth->confirmSms($code);
    } else {
        $key = $loginResult;
    }

    echo $key;

}
catch(Exception $exception)
{
    echo($exception->getMessage());
}