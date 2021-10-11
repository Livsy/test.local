<?php


class Home
{
    use SimpleView;

    /**
     * @var Параметры адресной строки
     */
    var $params = null;

    /**
     * Home constructor.
     * @param $params
     */
    function __construct(&$params)
    {
        $this->params = $params;
    }


    /**
     * @param $data
     * @return array
     */
    function sortForQuery($data)
    {
        $res = [];

        foreach(['name', 'email', 'status'] as $item)
        {
            if(isset($this->params['urlVars'][$item]))
            {
                $res = [$item, $this->params['urlVars'][$item]];
            }
        }

        return count($res) > 0 ? $res : ['', ''];
    }



    function isAdminWrite()
    {
        $ar = ['name', 'email', 'message'];

        $flag = 0;
        foreach($ar as $item) {
            if ($_SESSION['home']['post'][$item] != $_POST[$item])
            {
                $flag = 1;
            }
        }
        return $flag;
    }

    /**
     * @param DbMySqli $mysqli
     * @param Messages $messages
     */
    private function saveData(DbMySqli $mysqli, Messages $messages)
    {
        $errMessage = [
            'name' => 'Поле Имя не должно быть пустым',
            'email' => 'Поле Email не должно быть пустым',
            'message' => 'Поле Message не должно быть пустым',
        ];

        foreach($_POST as $key => $item)
        {
            $str = $mysqli->escape($item);

            if(strlen($str) == 0)
            {
                $_SESSION['home']['formMessage'][$key] = $errMessage[$key] ?? '';
            }

            $_SESSION['home']['post'][$key] = $str;
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        {
            $_SESSION['home']['formMessage']['email'] = 'Email введен не коррекно';
        }



        if(!isset($_SESSION['home']['formMessage']))
        {
            if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == true)
            {
                $_SESSION['home']['post']['status'] = ($_POST['status'] == 'on' ? 1 : 0);
                $_SESSION['home']['post']['isAdminWrite'] = $this->isAdminWrite();
                $method = (isset($_POST['id']) && strlen($_POST['id']) > 0) ? 'updateData' : 'addData';
                $messages->$method($_SESSION['home']['post']);
            }
            else if(isset($_POST['id']) && strlen($_POST['id']) > 0 && (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != true))
            {
                header('Location: /admin/login');
                exit;
            }
            else
            {
                $_SESSION['home']['post']['status'] = 0;
                $messages->addData($_SESSION['home']['post']);
            }
        }

        header('Location: /home');
        exit;
    }

    /**
     * Главная страница
     */
    function defaultMethod()
    {
        $mysqli = new DbMySqli($this->params['databaseConfig']);

        $messages = new \Messages($mysqli);

        if(isset($_POST) && count($_POST) > 0)
        {
            $this->saveData($mysqli, $messages);
        }

        $dataForm = [];
        if(isset($_SESSION['isAdmin'])
            && $_SESSION['isAdmin'] == true
            && isset($this->params['urlVars']['message'])
            && !empty($this->params['urlVars']['message']))
        {
            $dataForm = $messages->getMessageById($this->params['urlVars']['message']);
        }

        $limit = 3;

        $pageNum = $this->params['urlVars']['page'] ?? 1;

        list($sortField, $sortWay) = $this->sortForQuery($this->params['urlVars']);

        $res = $messages->getMessages(($pageNum-1) * $limit, $limit, $sortField, $sortWay);

        $messagesArray = $mysqli->eachAll($res);

        $countString = $mysqli->getCountString();

        if(!isset($_SESSION['home']['formMessage']) || count($_SESSION['home']['formMessage']) == 0)
        {
            unset($_SESSION['home']['post']);
        }

        $data = [
            'params'        => $this->params,
            'title'         => 'Home',
            'messages'      => $messagesArray,
            'countString'   => $countString,
            'limit'         => $limit,
            'numPage'       => $pageNum,
            'countPage'     => ceil($countString / $limit),
            'dataFormAdmin' => $dataForm,
            'session'       => $_SESSION
        ];

        /**
         * Отправляяем страницу пользователю
         */



        $this->View('home', $data);

        /**
         * Удаляем временные Данные
         */
        unset($_SESSION['home']['formMessage']);
        unset($_SESSION['home']['post']);
    }
}