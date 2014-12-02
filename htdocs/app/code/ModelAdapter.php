<?php

namespace App\Code;


use App\Code\Interfaces\DbAdapter;

class ModelAdapter implements DbAdapter
{

    /**
     * @var PdoClass
     */
    protected $dbAdapter;

    /**
     * @param PdoClass $dbAdapter
     */

    public function __construct(PdoClass $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function query()
    {
        // TODO: Implement query() method.
    }

    public function fetch()
    {
        // TODO: Implement fetch() method.
    }

    public function select()
    {
        // TODO: Implement select() method.
    }

    public function insert()
    {
        // TODO: Implement insert() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }

    public function getInsertId()
    {
        // TODO: Implement getInsertId() method.
    }

    public function countRows()
    {
        // TODO: Implement countRows() method.
    }

    public function getAffectedRows()
    {
        // TODO: Implement getAffectedRows() method.
    }
}