<?php


trait SimpleView {

    function View($template, $data, $defaultTemplate = 'default')
    {
        include $this->params['autoload']['PATH_VIEW'].$defaultTemplate.'.php';
    }
}