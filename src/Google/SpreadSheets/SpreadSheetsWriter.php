<?php
/**
 * Created by PhpStorm.
 * User: bko
 * Date: 15/05/08
 * Time: 12:13
 */

namespace Google\SpreadSheets;

use Google\SpreadSheets;
use ZendGData\SpreadSheets\ListEntry;

/**
 * Class SpreadSheetsWriter
 * @package Google\SpreadSheets
 */
class SpreadSheetsWriter
{
    /**
     * @var SpreadSheets
     */
    protected $client;

    /**
     * @var \ZendGData\Spreadsheets
     */
    protected $service;

    /**
     * @param SpreadSheets $client
     */
    public function __construct(SpreadSheets $client)
    {
        $this->client = $client;
        $this->service = $client->getService();
        return $this;
    }

    /**
     * @param $sheetKey
     * @param $worksheetId
     * @return $this
     */
    public function to($sheetKey, $worksheetId)
    {
        $this->client->setTarget($sheetKey, $worksheetId);
        return $this;
    }

    /**
     * @param array $row
     * @return ListEntry
     */
    public function insert(array $row)
    {
        return $this->service->insertRow($row,
            $this->client->getSheetKey(),
            $this->client->getWorksheetId()
        );
    }

    /**
     * @param array $row
     * @param array $identify
     * @return mixed
     * @throws SpreadSheetsException
     */
    public function update(array $row, array $identify)
    {
        $entries = $this->searchEntry($identify);
        $count = count($entries);
        if ($count > 1) {
            throw new SpreadSheetsException(sprintf(
                'Found multiple rows with the following params: %s', var_export($identify, true)
            ));
        } elseif ($count === 0) {
            throw new SpreadSheetsException(sprintf(
                'Not Found any rows with the following params: %s', var_export($identify, true)
            ));
        }
        return $this->service->updateRow($entries[0], $row);
    }

    /**
     * @param array $identifier
     * @return array|\ZendGData\App\Entry
     */
    public function searchEntry(array $identifier)
    {
        return $this->client->getReader()->getListFeed($identifier)->getEntry();
    }

}
