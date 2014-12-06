<?php
namespace app\code;

class DbQuery extends Object
{

    /**
     * @var \PDO
     */
    protected $dbAdapter;

    protected $tableName;

    protected $tableFields = array();

    protected $joinTable = array();

    protected $selectFields = array();

    protected $limit;

    protected $offset = 0;

    protected $on;

    protected $orderBy;

    protected $where;

    protected $tableAbbr;

    public function selectFields($fields = array())
    {
        if (empty($fields))
            return null;
        $this->tableFieldsCollected();
        foreach ($fields as $field) {
            if (($column = $this->getField($field)) != null) {
                $this->selectFields[] = $column;
            }
        }
    }

    protected function tableFieldsCollected()
    {
        if (empty($this->tableFields)) {
            $this->getTableColumns();
        }
    }

    public function getTableColumns()
    {
        $statement = $this->dbAdapter->query("SHOW columns FROM {$this->tableName}");
        if ($statement->execute()) {
            while ($record = $statement->fetch()) {
                $this->tableFields[$record['Field']] = $record['Type'];
            }

            return $this->tableFields;
        }

        return $this;
    }

    public function getField($key)
    {
        $this->tableFieldsCollected();

        if (!isset($this->tableFields[$key]))
            return null;

        return $this->formatField($key);
    }

    protected function formatField($tableField = null)
    {
        return sprintf('`%s`.`%s`', $this->getTableName(), $tableField);
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    protected function runLeftJoinQuery()
    {
        $fieldsToSelect[] = $this->makeSelectableTableFields(true);

        $leftJoin = array();

        $query = 'select ';

        if (!empty($this->joinTable)) {
            /** @var ModelAdapter $table */
            foreach ($this->joinTable as $table) {
                if ($table->hasLimit()) {
                    $leftJoin[] = $table->getLeftJoinWithLimitSQL();
                } else {
                    $leftJoin[] = $table->getLeftJoinSQL();
                }
                $fieldsToSelect[] = $table->makeSelectableTableFields(true);
            }
        }

        $query .= implode(',', $fieldsToSelect);
        $query .= $this->getFromSQL() . ' ';
        $query .= implode(' ', $leftJoin) . ' ';
        $query .= $this->getWhere();

        $prepare = $this->dbAdapter->query($query);
        if (!$prepare->execute())
            return false;
        return $prepare->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function makeSelectableTableFields($abbr = false)
    {

        $this->tableFieldsCollected();
        $tableFields = array();

        foreach ($this->tableFields as $field => $type) {
            if ($abbr) {
                $tableFields[] = $this->setTableAbbr($this->getField($field), $field);
            } else {
                $tableFields[] = $this->getField($field);
            }
        }

        return implode(',', $tableFields);
    }

    protected function hasLimit()
    {
        return $this->getLimit() > 0;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    protected function getLeftJoinWithLimitSQL()
    {
        $query = 'left join ( select ' .
            $this->makeSelectableTableFields(false)
            . ' from `%s`';

        if ($this->getWhere() != null) {
            $query .= ' ' . $this->getWhere();
        }

        if ($this->getOrderBy() != null) {
            $query .= ' order by ' . $this->getOrderBy() . ' desc';
        }
        if ($this->hasLimit()) {
            $query .= ' limit ' . $this->getOffset() . ',' . $this->getLimit();
        }

        $query .= ' ) as `%s` on ' .
            $this->getOn();

        return sprintf(
            $query,
            $this->getTableName(),
            $this->getTableName()
        );
    }

    /**
     * @return mixed
     */
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * @param $where
     * @param $value
     * @param string $opt
     * @return $this
     */
    public function setWhere($where, $value, $opt = '=')
    {
        $this->where = sprintf(' where %s %s %s',
            $where,
            $opt,
            $value
        );

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderBy()
    {
        return $this->formatField($this->orderBy);
    }

    /**
     * @param $orderBy
     * @return $this
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param $offset
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOn()
    {
        return $this->on;
    }

    /**
     * @param null $key
     * @param null $parent
     * @return $this
     */
    public function setOn($key = null, $parent = null)
    {
        $this->on = sprintf('(%s = %s)', $key, $parent);

        return $this;
    }

    protected function getLeftJoinSQL()
    {
        return sprintf('left join `%s` as `%s` on %s ',
            $this->getTableName(),
            $this->getTableName(),
            $this->getOn()
        );
    }

    protected function getFromSQL()
    {
        return sprintf('from `%s` as `%s`', $this->getTableName(), $this->getTableName());
    }

    protected function setTableAbbr($column, $field)
    {
        if (empty($this->tableAbbr))
            return null;

        return $column . ' as ' . $this->tableAbbr . '_' . $field . ' ';
    }
} 