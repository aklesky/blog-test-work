<?php

namespace App\Code;

use App\Code\Interfaces\DbAdapter;

class ModelAdapter implements DbAdapter
{

    const SET = 'set';

    const GET = 'get';

    /**
     * @var \PDO
     */
    protected $__dbAdapter;

    protected $__tableName;

    protected $model;

    private $_recordData = array();

    public function __construct()
    {
        /** @var Database $database */
        $database = Database::getInstance();
        $this->__dbAdapter = $database->getAdapter();
        $this->model = new \ReflectionClass($this);
        $this->__tableName = mb_strtolower($this->model->getShortName());
    }

    public function __set($key, $value)
    {
        $this->_recordData[mb_strtolower($key)] = array(
            'fieldName' => $key,
            'value' => $value
        );
    }

    public function __call($key, $value)
    {
        if (preg_match('/^(get|set)+(.*)$/i', $key, $match)) {
            $method = mb_strtolower($match[1]);
            if ($method == self::GET) {
                return $this->get($match[2]);
            } else if ($method == self::SET) {
                return $this->set($match[2], !empty($value) ? $value[0] : null);
            }
        }

        return $this;
    }

    private function get($key)
    {
        $key = mb_strtolower($key);
        if (isset($this->_recordData[$key]))
            return $this->_recordData[$key]['value'];

        return null;
    }

    public function set($key, $value)
    {
        $key = mb_strtolower($key);
        if (isset($this->_recordData[$key])) {
            $originalValue = $this->get($key);
            $this->_recordData[$key]['original'] = $originalValue;
            $this->_recordData[$key]['value'] = $value;
        }

        return $this;
    }

    public function save()
    {
        // TODO: Implement insert() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function delete()
    {
        if (isset($this->id)) {
            return $this->deleteById($this->id);
        }

        return false;
    }

    public function deleteById($id)
    {
        $prepare = $this->__dbAdapter->prepare(
            "delete from `{$this->__tableName}` where `Id` = :id"
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

        $prepare = $this->__dbAdapter->prepare(
            "select * from `{$this->__tableName}` where Id = :id"
        );
        $prepare->bindParam(':id', $id, \PDO::PARAM_INT);
        if ($prepare->execute())
            return $prepare->fetchObject($this->model->getName());

        return false;
    }

    public function selectAll()
    {
        $prepare = $this->__dbAdapter->prepare(
            "select * from  {$this->__tableName}"
        );

        if ($prepare->execute() && $prepare->rowCount() > 0) {
            $collection = array();
            while ($record = $prepare->fetchObject($this->model->getName())) {
                $collection[] = $record;
            }

            return $collection;
        }

        return null;
    }

    public function deleteAll()
    {
        $prepare = $this->__dbAdapter->prepare("delete from `{$this->__tableName}`");

        if ($prepare->execute())
            return $prepare->rowCount();

        return false;
    }

    public function getLastInsertId()
    {
        return $this->__dbAdapter->lastInsertId();
    }
}