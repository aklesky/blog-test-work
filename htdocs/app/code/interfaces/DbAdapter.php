<?php

namespace App\Code\Interfaces;


interface DbAdapter
{

    public function query();

    public function fetch();

    public function select();

    public function insert();

    public function update();

    public function delete();

    public function getLastInsertId();

    public function countRows();

    public function getAffectedRows();
} 