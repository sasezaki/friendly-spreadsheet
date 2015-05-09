<?php
/**
 * Created by PhpStorm.
 * User: bko
 * Date: 15/05/08
 * Time: 12:13
 */

namespace Google\SpreadSheets;

use Google\SpreadSheets;
use ZendGData\App\Entry;
use ZendGdata\SpreadSheets\Extension\Custom;
use ZendGData\SpreadSheets\ListEntry;
use ZendGData\Spreadsheets\ListFeed;
use ZendGData\Spreadsheets\ListQuery;

/**
 * Class SpreadSheetsReader
 * @package Google\SpreadSheets
 */
class SpreadSheetsReader
{
    /**
     * @var SpreadSheets
     */
    protected $client;

    protected $items  = [];
    protected $select = [];
    protected $where = [];

    /**
     * @param SpreadSheets $client
     */
    public function __construct(SpreadSheets $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @param $sheetKey
     * @param $worksheetId
     * @return $this
     */
    public function from($sheetKey, $worksheetId)
    {
        $this->client->setTarget($sheetKey, $worksheetId);
        return $this;
    }

    /**
     * @param array $identifier
     * @return $this
     */
    public function where(array $identifier)
    {
        $this->where = $identifier;
        return $this;
    }

    /**
     * @return array
     */
    public function fetchAll()
    {
        return $this->getItems();
    }

    /**
     * @return array
     */
    public function fetch()
    {
        return $this->first();
    }

    /**
     * @return array
     */
    public function first()
    {
        return array_slice($this->getItems(), 0, 1)['0'];
    }

    /**
     * @return array
     */
    public function last()
    {
        return array_slice($this->getItems(), -1, 1)['0'];
    }


    /**
     * @param int $nth
     * @return array
     */
    public function nth($nth)
    {
        return array_slice($this->items, $nth -1, 1)['0'];
    }

    /**
     * @param array $select
     * @return $this
     */
    public function select(array $select)
    {
        $this->select = $select;

        return $this;
    }


    /**
     * @param array $identifier
     * @return ListFeed
     */
    public function getListFeed($identifier = [])
    {
        $listQuery = new ListQuery();
        $listQuery->setSpreadsheetKey($this->client->getSheetKey())
              ->setWorksheetId($this->client->getWorksheetId());
        if (!empty($identifier)) {
            $criteria = $this->convertToCriteria($identifier);
            $listQuery->setSpreadsheetQuery($criteria);
        }
        return $this->client->getService()->getListFeed($listQuery);
    }

    /**
     * @param array $identify
     * @return string
     */
    protected function convertToCriteria(array $identify)
    {
        $queries = [];
        foreach ($identify as $k => $v) {
            $queries[] = sprintf('%s=%s', $k, strtolower($v));
        }
        return implode(' and ', $queries);
    }

    /**
     * @return array
     */
    protected function getItems()
    {
        if (!$this->needsFilter()) {
            return $this->items;
        }
        $items = [];
        foreach ($this->items as $item) {
            $items[] = $this->filter($item, $this->select);
        }
        return $items;
    }

    /**
     * @return bool
     */
    protected function needsFilter()
    {
        $select = $this->select;
        return !in_array('all', $select) && !in_array('*', $select);
    }

    /**
     * @param array $item
     * @param array $keys
     * @return array
     */
    protected function filter(array $item, array $keys)
    {
        foreach($item as $k => $value) {
            if (!in_array($k, $keys)) {
                unset($item[$k]);
            }
        }
        return $item;
    }

    /**
     * @param ListEntry $feed
     * @param callable $fn
     */
    protected function eachRows($feed, \Closure $fn)
    {
        foreach ($feed->getCustom() as $row) {
            $fn($row);
        }
    }

    /**
     * @return $this
     */
    public function exec()
    {
        $items = [];
        $feeds = empty($this->where) ? $this->getListFeed() : $this->getListFeed($this->where);
        foreach ($feeds as $feed) {
            $item = [];
            $this->eachRows($feed, function(Custom $row) use (&$item) {
                $item[$row->getColumnName()] = $row->getText();
            });
            $items[] = $item;
        }
        $this->items = $items;
        return $this;
    }

}
