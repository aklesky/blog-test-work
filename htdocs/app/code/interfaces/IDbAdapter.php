<?php

namespace App\Code\Interfaces;

use App\Code\ModelAdapter;

interface IDbAdapter
{
    public function selectById($id);

    public function selectAll();

    public function save();

    public function delete();

    public function deleteById($id);

    public function deleteAll();

    public function create();

    public function leftJoin(ModelAdapter $table = null);
} 