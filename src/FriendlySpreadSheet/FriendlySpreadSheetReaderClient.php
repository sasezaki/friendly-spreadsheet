<?php
/**
 * Created by PhpStorm.
 * User: bko
 * Date: 15/05/11
 * Time: 12:30
 */

namespace FriendlySpreadSheet;

class FriendlySpreadSheetReaderClient extends FriendlySpreadSheetAbstractClient
{
    protected $select      = ['*'];
    protected $where       = '';
    protected $maxResults  = null;
    protected $rows        = [];
    protected $sort        = '';
    protected $order       = '';

    public function select($select = [])
    {
        $this->select = $select;
        return $this;
    }

    public function from($spreadsheet, $worksheet)
    {
        $this->spreadsheet = $spreadsheet;
        $this->worksheet   = $worksheet;
        return $this;
    }

    public function where($identifier = '')
    {
        $this->where = $identifier;
        return $this;
    }

    public function fetch()
    {
        return array_slice($this->getRows(), 0, 1)['0'];
    }

    public function fetchAll()
    {
        $rows = $this->getRows();
        $maxResults = $this->maxResults;
        if (is_null($maxResults)) {
            return $rows;
        }
        return array_slice($rows, 0, $maxResults);
    }

    /**
     * @return $this
     * @throws FriendlySpreadSheetException
     */
    public function exec()
    {
        // fetch
        $worksheet = $this->getWorksheet();
        $query = [];
        if (strlen($this->where) > 0) {
            $query = ['sq' => $this->where];
        }
        $listFeed = $worksheet->getListFeed($query);
        $entries = $listFeed->getEntries();
        $rows = [];
        foreach ($entries as $entry) {
            $rows[] = $entry->getValues();
        }
        $this->rows = $this->sortRows($rows);
        return $this;
    }

    /**
     * @param $sort
     * @param $order
     * @return $this
     */
    public function orderBy($sort, $order = 'ASC')
    {
        $this->sort = $sort;
        $this->order = $order;
        return $this;
    }

    /**
     * @param $numeric
     * @return $this
     * @throws FriendlySpreadSheetException
     */
    public function setMaxResults($numeric)
    {
        if (!is_numeric($numeric) or $numeric == 0) {
            throw new FriendlySpreadSheetException('max results must be integer and bigger than zero');
        }
        $this->maxResults = intval($numeric);
        return $this;
    }

    /**
     * @return array
     */
    protected function getRows()
    {
        $select = $this->select;
        if (in_array('*', $select)) {
            return $this->rows;
        }
        $rows = [];
        foreach ($this->rows as $row) {
            $rows[] = $this->filterRows($row, $select);
        }
        return $rows;
    }

    /**
     * @param array $rows
     * @return array
     */
    protected function sortRows(array $rows)
    {
        if (strlen($this->sort) === 0) {
            return $rows;
        }
        usort($rows, [$this, 'sort']);
        return $rows;
    }


    /**
     * @param $a
     * @param $b
     * @return int
     */
    protected function sort($a, $b)
    {
        $sortKey = $this->sort;
        $order   = strtolower($this->order);
        if ($a[$sortKey] == $b[$sortKey]) {
            return 0;
        }
        $smaller = 1;
        $bigger  = -1;
        if ($order == 'asc') {
            $smaller = -1;
            $bigger  = 1;
        }
        return $a[$sortKey] < $b[$sortKey] ? $smaller : $bigger;
    }

    /**
     * @param array $row
     * @param array $select
     * @return array
     */
    protected function filterRows(array $row, array $select)
    {
        foreach ($row as $column => $value) {
            if (!in_array($column, $select)) {
                unset($row[$column]);
            }
        }
        return $row;
    }

}
