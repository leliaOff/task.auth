<?php

/**
 * Интрефейс: стандартная авторизация
 */
interface AuthStrategy
{
    public function login($login, $password): string;
}

/**
 * Интрефейс: авторизация с подтверждением по СМС
 */
interface AuthSmsStrategy extends AuthStrategy
{
    public function confirmSms($code): string;
}

/**
 * Авторизация в Телеграмм
 */
class TelegramAuthStrategy implements AuthSmsStrategy
{
    
    private $key;
    
    public function login($login, $password): string
    {
        if($password != '12345678') {
            return 'incorrect';
        }

        $this->key = hash('sha256', $password);
        return 'sms';
    }

    public function confirmSms($code): string
    {
        if($code != '666') {
            return 'incorrect';
        }

        return $this->key;
    }
    
}

/**
 * Авторизация в Вконтакте
 */
class VkontakteAuthStrategy implements AuthSmsStrategy
{
    
    private $key;
    
    public function login($login, $password): string
    {
        if($password != '12345678') {
            return 'incorrect';
        }

        $this->key = hash('sha256', $password);
        return 'sms';
    }

    public function confirmSms($code): string
    {
        if($code != '666') {
            return 'incorrect';
        }

        return $this->key;
    }
}

/**
 * Авторизация в Фейсбук
 */
class FacebookAuthStrategy implements AuthSmsStrategy
{
    
    private $key;
    
    public function login($login, $password): string
    {
        if($password != '12345678') {
            return 'incorrect';
        }

        $this->key = hash('sha256', $password);
        return 'sms';
    }

    public function confirmSms($code): string
    {
        if($code != '666') {
            return 'incorrect';
        }

        return $this->key;
    }
}

/**
 * Авторизация в Одноклассники
 */
class OdnoklassnikiAuthStrategy implements AuthStrategy
{
    
    private $key;
    
    public function login($login, $password): string
    {
        if($password != '12345678') {
            return 'incorrect';
        }

        $this->key = hash('sha256', $password);
        return $this->key;
    }
}

/**
 * Авторизация в Твитер
 */
class TwitterAuthStrategy implements AuthStrategy
{
    
    private $key;
    
    public function login($login, $password): string
    {
        if($password != '12345678') {
            return 'incorrect';
        }

        $this->key = hash('sha256', $password);
        return $this->key;
    }
}

/**
 * Работа со стратегиями авторизации
 */
class Context
{
    private $authStrategy;

    public function __construct(AuthStrategy $strategy)
    {
        $this->authStrategy = $strategy;
    }

    /**
     * Вход
     */
    public function login($login, $password)
    {
        return $this->authStrategy->login($login, $password);
    }

    /**
     * Проверка кода СМС
     */
    public function confirmSms($code)
    {
        return $this->authStrategy->confirmSms($code);
    }
}

/**
 * Авторизация
 */
class Auth
{    
    
    private $context;
    
    /**
     * Получаем данные пользователя
     */
    private function getUserData($login)
    {
        require_once "data.php";

        $users = array_filter($dataUsers, function($item) use ($login) {
            if($item['login'] == $login) return 1;
            return 0;
        });

        if(count($users) == 0) {
            return false;
        }

        return array_shift($users);
    }


    /**
     * Поптыка входа в систему
     */
    public function login($login, $password)
    {
        
        $user = $this->getUserData($login);
        if($user == false) {
            throw new Exception('user not found');
        }

        switch($user['type']) {
            case 'telegram':
                $this->context = new Context(new TelegramAuthStrategy());
                break;
            case 'vkontakte':
                $this->context = new Context(new VkontakteAuthStrategy());
                break;
            case 'facebook':
                $this->context = new Context(new FacebookAuthStrategy());
                break;
            case 'odnoklassniki':
                $this->context = new Context(new OdnoklassnikiAuthStrategy());
                break;
            case 'twitter':
                $this->context = new Context(new TwitterAuthStrategy());
                break;
        }
        
        $loginResult = $this->context->login($login, $password);

        if($loginResult == 'incorrect') {
            throw new Exception('password is incorrect');
        }

        /**
         * Тут будет либо ключ сессии, либо sms, что обозначает, что надо запросить смс-код у пользователя
         */
        return $loginResult;
    }

    /**
     * Проверка кода СМС
     */
    public function confirmSms($code)
    {
        if($this->context == null) {
            throw new Exception('you are not login');
        }

        return $this->context->confirmSms($code);
    }

}