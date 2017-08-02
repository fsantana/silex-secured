<?php
/**
 * Created by PhpStorm.
 * User: fsjrb
 * Date: 02/08/2017
 * Time: 11:49
 */

namespace ExtratoLista\Models;


class User
{
    public $data = [];


    public function __construct()
    {
        $this->data['admin'] = ['username'=>'admin','password'=>'admin', 'roles'=>['ROLE_ADMIN','ROLE_USER']];
        $this->data['user'] = ['username'=>'user','password'=>'user', 'roles'=>'ROLE_USER'];
    }



}