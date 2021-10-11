<?php

interface Database
{
    public function query($sql);

    public function escape($str);
}

class DbMySqli implements Database
{
    private $connect = null;

    function __construct($params)
    {
        $p = &$params;
        $this->connect = mysqli_connect($p['host'], $p['user'], $p['pass'], $p['database']);
        $this->query('SET NAMES utf8');
    }

    public function query($sql)
    {
        return mysqli_query($this->connect, $sql);
    }

    public function escape($str)
    {
        return mysqli_escape_string($this->connect, $str);
    }

    function each($res)
    {
        yield mysqli_fetch_array($res);
    }

    function eachAll($res)
    {
        return mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    function getCountString()
    {
        $sql = 'SELECT FOUND_ROWS() as count; ';

        $res = $this->query($sql);

        $row = mysqli_fetch_array($res);

        return $row['count'];
    }
}
