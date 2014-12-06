<?php

namespace App\Code;

use App\Code\Interfaces\IDbAdapter;

class ModelAdapter extends Object implements IDbAdapter
{

    const SET = 'set';

    const GET = 'get';

    /**
     * @var \PDO
     */
    protected $dbAdapter;

    protected $tableName;

    protected $model;

    protected $recordData = array();

    protected $id;

    protected $errorMessage;

    public function __construct()
    {
        /** @var Database $database */
        $database = Database::getInstance();
        $this->dbAdapter = $database->getAdapter();
        $this->model = new \ReflectionClass($this);
        $this->tableName = $this->capitalsToUnderscore($this->model->getShortName());
    }

    public function __set($key, $value)
    {
        if (mb_strtolower($key) == 'id') {
            $this->id = $value;
        } else {
            $this->recordData[mb_strtolower($key)] = array(
                'fieldName' => $key,
                'value' => $value,
                'original' => $value
            );
        }

        return $this;
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
        $key = $this->capitalsToUnderscore($key);
        if (isset($this->recordData[$key]))
            return $this->recordData[$key]['value'];

        return null;
    }

    public function set($key, $value)
    {

        $key = $this->capitalsToUnderscore($key);
        if (isset($this->recordData[$key])) {
            $this->recordData[$key]['original'] = $this->get($key);
            $this->recordData[$key]['value'] = $value;
        }

        return $this;
    }

    /**
     * @return bool|ModelAdapter
     */
    public function create()
    {
        if (($fields = $this->getTableColumns())) {
            $object = new static();
            foreach ($fields as $field => $type) {
                $object->$field = ($type == 'datetime') ?
                    date('Y-d-m H:i:s') : null;
            }

            return $object;
        }

        return false;
    }

    public function getTableColumns()
    {
        $statement = $this->dbAdapter->query("SHOW columns FROM {$this->tableName}");
        if ($statement->execute()) {
            $fields = array();
            while ($record = $statement->fetch()) {
                $fields[$record['Field']] = $record['Type'];
            }

            return $fields;
        }

        return null;
    }

    /**
     * @return $this|ModelAdapter|bool
     */
    public function save()
    {
        if ($this->getId() !== null) {
            return $this->update();
        }

        return $this->insert();
    }

    public function getId()
    {
        return $this->id;
    }

    protected function update()
    {
        $updateData = array();
        foreach ($this->recordData as $record) {
            $record['value'] = $this->dbAdapter->quote($record['value']);
            $updateData[] = "`{$record['fieldName']}`={$record['value']}";
        }

        $prepare = $this->dbAdapter->prepare(
            "update {$this->tableName} set " . implode(',', $updateData) .
            " where `Id`= :id"
        );

        $prepare->bindParam(":id", $this->getId(), \PDO::PARAM_INT);

        if (!$prepare->execute())
            return false;

        return $this;
    }

    protected function insert()
    {

        foreach ($this->recordData as $record) {
            $fields[] = $record['fieldName'];
            $values[] = $this->dbAdapter->quote($record['value']);
        }
        $prepare = $this->dbAdapter->prepare(
            "insert into `{$this->tableName}` (`" . implode('`,`', $fields) . "`) values " .
            "(" . implode(",", $values) . ")"
        );

        if (!$prepare->execute())
            return false;

        $object = $this->selectById($this->dbAdapter->lastInsertId());
        $this->setId($object->getId());

        return $this;
    }

    public function selectById($id = null)
    {
        if (empty($id))
            return false;

        return $this->selectOneBy('id', $id);
    }

    public function selectOneBy($field, $value)
    {
        if (empty($field) || empty($value))
            return false;

        $prepare = $this->dbAdapter->prepare(
            "select * from `{$this->tableName}` where `{$field}` = :value"
        );
        $prepare->bindParam(':value', $value);
        if ($prepare->execute())
            return $prepare->fetchObject($this->model->getName());

        return false;
    }

    private function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function delete()
    {
        if (($id = $this->getId()) != null) {
            return $this->deleteById($id);
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

    public function deleteAll()
    {
        $prepare = $this->dbAdapter->prepare("delete from `{$this->tableName}`");

        if ($prepare->execute())
            return $prepare->rowCount();

        return false;
    }

    public function selectAll()
    {
        $prepare = $this->dbAdapter->prepare(
            "select * from  {$this->tableName}"
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

    public function setData($array = null)
    {
        if (empty($array))
            return false;
        foreach ($array as $key => $value) {
            $this->set($key, $value);
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param mixed $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }
}