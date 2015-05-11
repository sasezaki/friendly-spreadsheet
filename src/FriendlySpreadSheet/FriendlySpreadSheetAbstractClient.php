<?php
/**
 * Created by PhpStorm.
 * User: bko
 * Date: 15/05/11
 * Time: 22:15
 */

namespace FriendlySpreadSheet;

use Google\Spreadsheet\SpreadsheetFeed;

abstract class FriendlySpreadSheetAbstractClient
{
    /**
     * @var SpreadsheetFeed
     */
    protected $spreadsheetFeed;
    protected $spreadsheet = '';
    protected $worksheet   = '';

    /**
     * @param SpreadsheetFeed $spreadsheetFeed
     */
    public function __construct(SpreadsheetFeed $spreadsheetFeed)
    {
        $this->spreadsheetFeed = $spreadsheetFeed;
        return $this;
    }

    /**
     * @return \Google\Spreadsheet\Spreadsheet
     * @throws FriendlySpreadSheetException
     */
    protected function getSpreadsheet()
    {
        $spreadsheet = $this->spreadsheetFeed->getByTitle($this->spreadsheet);
        if (is_null($spreadsheet)) {
            throw new FriendlySpreadSheetException(sprintf('Spreadsheet %s is not found', $this->spreadsheet));
        }
        return $spreadsheet;
    }

    /**
     * @return \Google\Spreadsheet\Worksheet
     * @throws FriendlySpreadSheetException
     */
    protected function getWorksheet()
    {
        $spreadsheet = $this->getSpreadsheet();
        $worksheetFeed = $spreadsheet->getWorksheets();
        $worksheet = $worksheetFeed->getByTitle($this->worksheet);
        if (is_null($worksheet)) {
            throw new FriendlySpreadSheetException(sprintf('Worksheet %s is not found', $this->worksheet));
        }
        return $worksheet;
    }

}