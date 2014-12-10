<?php

namespace App\Code\Models;

use App\Code\ModelAdapter;

class Blog extends ModelAdapter
{

    protected $tableAbbr = 'bl';

    public function selectBlogByUserId($userId = null)
    {
        return $this->selectOneBy('user_id', $userId);
    }
}