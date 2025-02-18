<?php

namespace App;

class Permission
{

    public $group;
    public $name;
    public $access = ['view', 'create', 'update'];

    public function __construct($group = null, $name = null, $access = null)
    {
        $this->group = $group;
        $this->name = $name;
        if ($access !== null) {
            $this->access = $access;
        }
    }
}
