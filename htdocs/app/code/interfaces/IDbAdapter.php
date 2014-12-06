<?php

namespace App\Code\Interfaces;

interface IDbAdapter
{
    public function selectById($id);

    public function selectAll();

    public function save();

    public function delete();

    public function deleteById($id);

    public function deleteAll();

    public function create();
} 