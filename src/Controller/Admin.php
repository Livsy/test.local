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
        $flag = true;

        if(!isset($_POST['email']) || $_POST['email'] != 'admin')
        {
            $flag = false;
        }

        if(!isset($_POST['pass']) || $_POST['pass'] != '123')
        {
            $flag = false;
        }


        if(!$flag)
        {
            $_SESSION['admin']['form']['message'] = 'Логин или пароль не верны';
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