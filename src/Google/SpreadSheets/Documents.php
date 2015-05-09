<?php
/**
 * Created by PhpStorm.
 * User: bko
 * Date: 15/05/08
 * Time: 14:09
 */

namespace Google\SpreadSheets;

use Google\SpreadSheets;
use ZendGData\Spreadsheets\DocumentQuery;

class Documents
{
    protected $sheetKey = '';

    /**
     * @param SpreadSheets $client
     * @param string $sheetKey
     */
    public function __construct(SpreadSheets $client, $sheetKey = '')
    {
        $this->client = $client;
        $this->sheetKey = $sheetKey;
        return $this;
    }

    /**
     * @param $sheetKey
     * @return $this
     */
    public function where($sheetKey)
    {
        $this->sheetKey = $sheetKey;
        return $this;
    }

    public function all()
    {
        $sheetKey = $this->sheetKey;
        if (strlen(trim($sheetKey)) == 0) {
            throw new SpreadSheetsException(
                'sheet key is not set'
            );
        }
        return $this->findWorksheets($sheetKey);
    }

    /**
     * @param $sheetKey
     * @return array
     */
    public function findWorksheets($sheetKey)
    {
        $worksheets = [];
        $feed = $this->getWorksheetFeed($sheetKey);
        foreach ($feed->entries as $entry) {
            $worksheets[basename($entry->id)] = $entry->title;
        }
        return $worksheets;
    }

    /**
     * @param $sheetKey
     * @return \ZendGData\Spreadsheets\WorksheetFeed
     */
    protected function getWorksheetFeed($sheetKey)
    {
        $query = new DocumentQuery();
        $query->setSpreadsheetKey($sheetKey);
        $service = $this->client->getService();
        return $service->getWorksheetFeed($query);
    }

}
