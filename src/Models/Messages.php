<?php


class Messages
{
    var $db = null;

    var $field = ['name', 'email', 'message', 'status'];

    function __construct(DbMySqli $database)
    {
        $this->db = $database;
    }


    function getMessages($start = 0, $limit = 3, $sortField = '', $sortWay = '')
    {
        $order = '';

        $start = intval($this->db->escape($start));
        $limit = intval($this->db->escape($limit));
        $sortField = strip_tags($this->db->escape($sortField));
        $sortWay = strip_tags($this->db->escape($sortWay));

        if(strlen($sortField) > 0)
        {
            $order = ' ORDER BY '.$sortField.' '.$sortWay;
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS id, `name`, email, message, status, isAdminWrite 
                FROM messages '.$order.' LIMIT '.$start.', '.$limit;

        $res = $this->db->query($sql);

        return $res;
    }


    private function fieldsToString($data)
    {
        $ar = [];

        foreach($this->field as $item)
        {
            $ar[] = $item.'="'.$this->db->escape(strip_tags($data[$item])).'"';
        }

        return implode(', ', $ar);
    }



    function addData($data)
    {
        $sql = 'INSERT INTO messages SET '.$this->fieldsToString($data);

        $res = $this->db->query($sql);
    }


    function updateData($data)
    {
        $sql = 'UPDATE messages 
                SET '.$this->fieldsToString($data).', isAdminWrite="'.$data['isAdminWrite'].'" 
                WHERE id='.intval($this->db->escape($data['id']));

        $res = $this->db->query($sql);
    }



    function getMessageById($id)
    {
        $sql = 'SELECT id, `name`, email, message, status 
                FROM messages 
                WHERE id = '.intval($this->db->escape($id));

        $res = $this->db->query($sql);

        $data = $this->db->eachAll($res);

        return $data[0];
    }

}