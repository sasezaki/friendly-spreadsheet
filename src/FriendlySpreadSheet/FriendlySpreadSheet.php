<?php
/**
 * Created by PhpStorm.
 * User: bko
 * Date: 15/05/08
 * Time: 12:13
 */

namespace FriendlySpreadSheet;

use Google_Client;
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
use Google\Spreadsheet\SpreadsheetService;

class FriendlySpreadSheet
{
    protected $client;
    protected $config = [];

    public function __construct($accessToken)
    {
        $serviceRequest = new DefaultServiceRequest($accessToken);
        ServiceRequestFactory::setInstance($serviceRequest);
        $this->client = new SpreadsheetService();
    }

    public static function auth(array $config)
    {
        $accessToken = self::getAccessToken($config);
        return new FriendlySpreadSheet($accessToken);
    }

    public function createReaderClient()
    {
        return new FriendlySpreadSheetReaderClient($this->getSpreadsheetFeed());
    }

    public function createWriterClient()
    {
        return new FriendlySpreadSheetWriterClient($this->getSpreadsheetFeed());
    }

    public function listWorksheet($spreadsheet)
    {
        $worksheets = [];
        foreach ($this->getWorksheets($spreadsheet) as $worksheet) {
            $worksheets[] = $worksheet->getTitle();
        }
        return $worksheets;
    }

    protected function getWorksheets($spreadsheet)
    {
        $spreadsheetFeed = $this->getSpreadsheetFeed();
        return $spreadsheetFeed->getByTitle($spreadsheet)->getWorksheets();
    }

    protected function getSpreadsheetFeed()
    {
        return $this->client->getSpreadsheets();
    }

    protected static function getAccessToken(array $config)
    {
        self::validateConfig($config);
        $client = new Google_Client();
        $client->setApplicationName($config['application_name']);
        $client->setClientId ($config['client_id']);
        $client->setAssertionCredentials(new \Google_Auth_AssertionCredentials(
            $config['email'],
            array('https://spreadsheets.google.com/feeds','https://docs.google.com/feeds'),
            file_get_contents($config['p12_key_file']),
            $config['secret']
        ));
        $client->getAuth()->refreshTokenWithAssertion();
        $res = json_decode($client->getAccessToken());
        return $res->access_token;
    }

    protected static function validateConfig(array $config)
    {
        $requirements = self::getConfigRequirements();
        foreach ($requirements as $requirement) {
            if (!array_key_exists($requirement, $config)) {
                throw new FriendlySpreadSheetException(sprintf('config %s must be specify', $requirement));
            }
        }
    }

    protected static function getConfigRequirements()
    {
        return [
            'application_name',
            'client_id',
            'email',
            'p12_key_file',
            'secret',
        ];
    }
}
