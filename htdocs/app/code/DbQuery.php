<?php
namespace app\code;

class DbQuery extends Object
{

    /**
     * @var \PDO
     */
    protected $dbAdapter;

    protected $tableName;

    protected $joinTable = array();

    protected $selectFields = array();

    protected $limit;

    protected $offset = 0;

    protected $on;

    protected $orderBy;

    protected $where;

    protected $tableAbbr;

    protected $orderDirection = 'desc';

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

        if ($this->getWhere() != null) {
            $query .= ' ' . $this->getWhere();
        }

        if ($this->getOrderBy() != null) {
            $query .= ' order by ' . $this->getOrderBy() . ' ' . $this->orderDirection;
        }
        if ($this->hasLimit()) {
            $query .= ' limit ' . $this->getOffset() . ',' . $this->getLimit();
        }

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

    protected function setTableAbbr($column, $field)
    {
        if (empty($this->tableAbbr))
            return null;

        return $column . ' as ' . $this->tableAbbr . '_' . $field . ' ';
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
            $query .= ' order by ' . $this->getOrderBy() . $this->orderDirection;
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
        if (empty($this->orderBy))
            return null;

        return $this->formatField($this->orderBy);
    }

    /**
     * @param $orderBy
     * @param string $direction
     * @return $this
     */
    public function setOrderBy($orderBy, $direction = "desc")
    {
        $this->orderBy = $orderBy;
        $this->orderDirection = $direction;

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
} 