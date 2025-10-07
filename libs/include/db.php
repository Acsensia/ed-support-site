<?php

class QueryBuilder {

    private $db;
    protected $from = '';
    protected $select = [];
    protected $where = [];
    protected $order = [];
    protected $join = [];
    protected $offset = null;
    protected $limit = null;

    public function __construct($db, $from) {
        $this->from = $from;
        $this->db = $db;
    }

    public function offset($o) { $this->offset = $o; return $this; }
    public function limit($l) { $this->limit = $l; return $this; }

    public function leftJoin($t, $c) {
        $this->join[] = [
            'type' => ' LEFT JOIN ',
            'table' => $t,
            'condition' => $c
        ];
        return $this;
    }

    public function select($fields) {
        if (is_array($fields)) {
            foreach ($fields as $v) {
                array_push($this->select, $v);
            }
        } else {
            array_push($this->select, $fields);
        }
        return $this;
    }

    public function orderBy($fields) {
        if (is_array($fields)) {
            foreach ($fields as $v) {
                array_push($this->order, $v);
            }
        } else {
            array_push($this->order, $fields);
        }
        return $this;
    }

    public function where($field, $condition, $value) {
        if ($condition == 'eq') $condition = '=';
        else if ($condition == 'ne') $condition = '<>';
        else if ($condition == 'le') $condition = '<=';
        else if ($condition == 'lt') $condition = '<';
        else if ($condition == 'gt') $condition = '>';
        else if ($condition == 'ge') $condition = '>=';
        else if ($condition == 'bw') {
            $condition = 'LIKE';
            $value.='%';
        }
        else if ($condition == 'ew') {
            $condition = 'LIKE';
            $value='%'.$value;
        }
        else if ($condition == 'cn') {
            $condition = 'LIKE';
            $value='%'.$value.'%';
        }
        array_push($this->where,[
            'field' => $field,
            'condition' => $condition,
            'value' => $value
        ]);
        return $this;
    }

    private function buildWhere($where, $isJoin = false) {
        $wh = '';
        foreach($where as $item) {
            if ($wh) $wh .= ' AND';
            if ($item['condition'] == 'nc') {
                $wh .= ' ' . $item['field'] . " NOT LIKE '%" . $item['value'] . "%'";
            } else if ($item['condition'] == 'bn') {
                $wh .=  ' ' . $item['field'] . " NOT LIKE '". $item['value']. "%'";
            } else if ($item['condition'] == 'en') {
                $wh .=  ' ' . $item['field'] . " NOT LIKE '%". $item['value']. "'";
            } else if ($item['condition'] == 'IN') {
                $wh .=  ' ' . $item['field'] . ' '. $item['condition'].  ' ('. implode(',', $item['value']) . ')';
            } else {
                $wh .= ' ' . $item['field'] . ' ' . $item['condition'] . ((!$isJoin) ? " '" : " ") . $item['value'] . ((!$isJoin) ? "'" : "");
            }
        }

        return $wh;
    }

    public function sql()
    {
        $sql = 'SELECT ';
        if (!$this->select)
        {
            $sql .= ' * ';
        }else {
            $sql .= implode(',', $this->select);
        }
        $sql .= ' FROM ' . $this->from;
        foreach($this->join as $item) {
            $sql .= ' '. $item['type'] . ' ' . $item['table'] . ' ON ' . $this->buildWhere($item['condition'], true);
        }
        if ($this->where) {
            $sql .= ' WHERE '. $this->buildWhere($this->where );
        }
        if ($this->order) $sql .= ' ORDER BY ' . implode(',', $this->order);
        if ($this->offset && $this->limit) $sql .= ' LIMIT '. $this->offset . ',' . $this->limit;
        else if ($this->limit) $sql .= ' LIMIT '. $this->limit;
        return $sql;
    }

    public function count() {
        $c = $this->db->queryOne(str_replace( ' * ','COUNT(*) as count', $this->sql()));
        if (!$c) return false;
        return $c['count'];
    }


    public function rows() {
        return $this->db->query($this->sql());
    }

    public function mapRows($f) {
        return $this->db->queryMap($this->sql(), $f);
    }

    public function one() {
        return $this->db->queryOne($this->sql());
    }
}


class DB {
    private $options = [];
    private $con = null;
    public function __construct($options) {
        $this->options = $options;
        $this->connect();
    }

    public function find($from)
    {
        return new QueryBuilder($this, $from);
    }

    public function __destruct(){
        $this->close();
    }

    public function isConnect() {
        return ($this->con != null);
    }

    public function close() {
        if($this->con) mysqli_close($this->con);
    }

    public function error() {
        return mysqli_error($this->con);
    }

    public function connect() {
        $this->con = mysqli_connect($this->options['host'],$this->options['user'],$this->options['password'], $this->options['db']);
        return $this->isConnect();
    }
    public function query($sql) {
        if (!$this->isConnect()) return false;
        $r = mysqli_query($this->con, $sql);
        if (!$r) return false;
        $rows = [];
        while ($row = mysqli_fetch_assoc($r)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function queryMap($sql, $f) {
        if (!$this->isConnect()) return false;
        $r = mysqli_query($this->con, $sql);
        if (!$r) return false;
        $rows = [];
        while ($row = mysqli_fetch_assoc($r)) {
            $rows[$row[$f]] = $row;
        }
        return $rows;
    }

    public function queryOne($sql) {
        if (!$this->isConnect()) return false;
        $r = mysqli_query($this->con, $sql);
        if (!$r) return false;
        return mysqli_fetch_assoc($r);
    }

    public function insert($table, $ar)
    {
        if (!$this->isConnect()) return false;
        $fields = '';
        $values ='';
        $i=0;
        foreach ($ar as $k => $v) {
            if ($i > 0) {
                $fields.=',';
                $values.=',';
            }
            $fields .= '`'.$k."`";
            $values .= "'" . $v . "'";
            $i++;
        }
        $sql = "INSERT INTO `".$table."` ( ".$fields.") VALUES(".$values.")";
        $r = mysqli_query($this->con, $sql);
        if (!$r) return false;
        return true;
    }

    public function update($table, $id, $ar)
    {
        if (!$this->isConnect()) return false;
        $i=0;
        $sql = "UPDATE `".$table."` SET ";
        foreach ($ar as $k => $v) {
            if ($i > 0) {
                $sql .= ',';
            }
            $sql .= $k . ' = ' . "'" . $v . "'";
            $i++;
        }
        $sql .= ' WHERE id = ' . $id;
        $r = mysqli_query($this->con, $sql);
        if (!$r) return false;
        return true;
    }

    public function delete($table, $id)
    {
        if (!$this->isConnect()) return false;
        $r = mysqli_query($this->con, 'DELETE FROM ' . '`' . $table . '`' . " WHERE id='" . $id . "'");
        if (!$r) return false;
        return true;
    }

    public function beginTrans() {
        return mysqli_query($this->con, "START TRANSACTION");
    }
    public function rollbackTrans() {
        return mysqli_query($this->con, "ROLLBACK");
    }
    public function commitTrans() {
        return mysqli_query($this->con, "COMMIT");
    }
}
