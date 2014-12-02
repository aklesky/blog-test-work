<?php

namespace App\Code;


use App\Code\Interfaces\DbAdapter;

class ModelAdapter implements DbAdapter
{

    /**
     * @var PdoClass
     */
    protected $dbAdapter;

    protected $tableName;

    /**
     * @param PdoClass $dbAdapter
     */

    public function __construct(PdoClass $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;

        $reflection = new \ReflectionClass($this);
        $this->tableName = mb_strtolower($reflection->getShortName());
        unset($reflection);
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

    public function deleteById($id)
    {
        $prepare = $this->dbAdapter->prepare("delete from `{$this->tableName}` where `Id` = :id");
        $prepare->bindParam(':id', $id, \PDO::PARAM_INT);
        if($prepare->execute())
            return $prepare->rowCount();
        return false;
    }

    public function deleteAll()
    {
        $prepare = $this->dbAdapter->prepare("delete from `{$this->tableName}`");

        if($prepare->execute())
            return $prepare->rowCount();
        return false;
    }

    public function getLastInsertId()
    {
        return $this->dbAdapter->lastInsertId();
    }

    public function countRows()
    {

    }

    public function getAffectedRows()
    {
        // TODO: Implement getAffectedRows() method.
    }
}