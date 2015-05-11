<?php
/**
 * Created by PhpStorm.
 * User: bko
 * Date: 15/05/11
 * Time: 22:08
 */

namespace FriendlySpreadSheet;

class FriendlySpreadSheetWriterClient extends FriendlySpreadSheetAbstractClient
{
    /**
     * @param $spreadsheet
     * @param $worksheet
     * @return $this
     */
    public function to($spreadsheet, $worksheet)
    {
        $this->spreadsheet = $spreadsheet;
        $this->worksheet = $worksheet;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     * @throws FriendlySpreadSheetException
     */
    public function insert(array $row)
    {
        $worksheet = $this->getWorksheet();
        $worksheet->getListFeed()->insert($row);
        return $this;
    }

    /**
     * @param array $row
     * @param array $identifier
     * @return $this
     * @throws FriendlySpreadSheetException
     */
    public function update(array $row, array $identifier)
    {
        $worksheet = $this->getWorksheet();
        $query = ['sq' => http_build_query($identifier)];
        $listFeed = $worksheet->getListFeed($query);
        $entries = $listFeed->getEntries();
        foreach ($entries as $entry) {
            $values = $entry->getValues();
            $values = array_merge($values, $row);
            $entry->update($values);
        }
        return $this;
    }

}