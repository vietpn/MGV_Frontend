<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hatt
 * Date: 8/14/14
 * Time: 3:25 PM
 * To change this template use File | Settings | File Templates.
 */

class My_validator extends  CI_Form_validation{
    function __construct()
    {
        parent::__construct();
    }
    public function checklen($str)
    {
        if(strlen($str)<3)
        {
            return false;
        }else
            return true;
    }

}