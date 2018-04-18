<?php

interface AuthStrategy
{
    public function login($login, $password);
}

class TelegramAuthStrategy implements AuthStrategy
{
    public function login($login, $password)
    {
        // ...
    }
}

class VkontakteAuthStrategy implements AuthStrategy
{
    public function login($login, $password)
    {
        // ...
    }
}

class FacebookAuthStrategy implements AuthStrategy
{
    public function login($login, $password)
    {
        // ...
    }
}

class OdnoklassnikiAuthStrategy implements AuthStrategy
{
    public function login($login, $password)
    {
        // ...
    }
}

class TwitterAuthStrategy implements AuthStrategy
{
    public function login($login, $password)
    {
        // ...
    }
}

class Context
{
    private $authStrategy;

    public function __construct(AuthStrategy $strategy)
    {
        $authStrateg = $strategy;
    }

    public function login($login, $password)
    {
        return $this->authStrategy->login($login, $password);
    }
}

class Auth
{

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
        return $users[0];
    }

    public function login($login, $password)
    {
        
        $user = $this->getUserData($login);
        if($user == false) {
            throw new Exception('user not found');
        }

        return $user;

        switch($user['type']) {
            case 'telegram':
                $context = new Context(new TelegramAuthStrategy());
                break;
            case 'vkontakte':
                $context = new Context(new VkontakteAuthStrategy());
                break;
            case 'facebook':
                $context = new Context(new FacebookAuthStrategy());
                break;
            case 'odnoklassniki':
                $context = new Context(new OdnoklassnikiAuthStrategy());
                break;
            case 'twitter':
                $context = new Context(new TwitterAuthStrategy());
                break;
        }
        
        $loginResult = $context->login($login, $password);
        if($loginResult == false) {
            throw new Exception('password is incorrect');
        }
    }

}