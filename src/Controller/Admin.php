<?php


class Admin
{
    use SimpleView;

    function __construct(&$params)
    {
        $this->params = $params;
    }

    function defaultMethod()
    {
        // Если админ не авторизирован
        $this->View('login', [], 'Empty');
    }

    function isAdmin()
    {
        $flagEmail = true;
        $flagPass = true;
        $flag = true;

        if(!isset($_POST['email']) || $_POST['email'] != 'admin')
        {
            $flag = $flagEmail = false;
        }

        if(!isset($_POST['pass']) || $_POST['pass'] != '123')
        {
            $flag = $flagPass = false;
        }


        if(!$flagEmail && !$flagPass)
        {
            $_SESSION['admin']['form']['message'] = 'Введенные данные не верные';
        }
        else if(!$flagEmail || !$flagPass)
        {
            $_SESSION['admin']['form']['message'] = 'Неправильные реквизиты доступа';
        }

        return $flag;
    }


    function login()
    {
        if(isset($_POST) && count($_POST) > 0 && $this->isAdmin())
        {
            $_SESSION['isAdmin'] = true;
            header('Location: /home');
            exit;
        }


        $this->View('login', [], 'empty');

        unset($_SESSION['admin']['form']);
    }


    function logout()
    {
        unset($_SESSION['isAdmin']);
        header('Location: /home');
        exit;
    }

}