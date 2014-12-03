<?php

namespace App\Code;

use App\Code\Interfaces\DbAdapter;

class ModelAdapter implements DbAdapter
{

    /**
     * @var \PDO
     */
    protected $dbAdapter;

    protected $tableName;

    protected $model;




    public function __construct()
    {
        /** @var Database $database */
        $database = Database::getInstance();
        $this->dbAdapter = $database->getAdapter();
        $this->model = new \ReflectionClass($this);
        $this->tableName = mb_strtolower($this->model->getShortName());
    }

    public function save()
    {
        // TODO: Implement insert() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function delete() {
        if(isset($this->id)){
            return $this->deleteById($this->id);
        }
        return false;
    }

    public function deleteById($id)
    {
        $prepare = $this->dbAdapter->prepare(
            "delete from `{$this->tableName}` where `Id` = :id"
        );
        $prepare->bindParam(':id', $id, \PDO::PARAM_INT);

        if ($prepare->execute())
            return $prepare->rowCount();

        return false;
    }

    public function selectById($id = null)
    {
        if (empty($id))
            return false;

        $prepare = $this->dbAdapter->prepare(
            "select * from `{$this->tableName}` where Id = :id"
        );
        $prepare->bindParam(':id', $id, \PDO::PARAM_INT);
        if ($prepare->execute())
            return $prepare->fetchObject($this->model->getName());

        var_dump($prepare->errorInfo());
        return false;
    }

    public function selectAll()
    {
        $prepare = $this->dbAdapter->prepare(
            "select * from  {$this->tableName}"
        );

        if ($prepare->execute() && $prepare->rowCount() > 0) {
            $collection = array();
            while($record = $prepare->fetchObject($this->model->getName())) {
                $collection[] = $record;
            }
            return $collection;
        }
        return null;
    }

    public function deleteAll()
    {
        $prepare = $this->dbAdapter->prepare("delete from `{$this->tableName}`");

        if ($prepare->execute())
            return $prepare->rowCount();

        return false;
    }

    public function getLastInsertId()
    {
        return $this->dbAdapter->lastInsertId();
    }
}