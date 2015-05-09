<?php
/**
 * Created by PhpStorm.
 * User: bko
 * Date: 15/05/08
 * Time: 12:13
 */

namespace Google;

use Google\SpreadSheets\Documents;
use Google\SpreadSheets\SpreadSheetsException;
use Google\SpreadSheets\SpreadSheetsReader;
use Google\SpreadSheets\SpreadSheetsWriter;
use Zend\Http\Client\Adapter\Curl;
use ZendGData\ClientLogin;
use ZendGData\HttpClient;
use ZendGData\Spreadsheets as ZendSpreadSheets;

/**
 * Class SpreadSheets
 * @package Google
 */
class SpreadSheets
{
    protected $user;
    protected $password;
    protected $sheetKey;
    protected $worksheetId;

    protected $userDefinition = [
        'user',
        'password',
    ];

    /**
     * @var ZendSpreadSheets
     */
    protected $service;

    const ENTRY_POINT = 'https://spreadsheets.google.com/feeds/spreadsheets/';

    /**
     * @param array $user
     * @throws SpreadSheetsException
     */
    public function __construct(array $user)
    {
        if (!$this->validateUserArray($user)) {
            throw new SpreadSheetsException('Array $user needs to set `user` and `password`');
        }
        $this->user     = $user['user'];
        $this->password = $user['password'];
        $this->initSpreadSheetsService();
    }

    /**
     * @param array $user
     * @return SpreadSheets
     */
    public static function login(array $user)
    {
        return new SpreadSheets($user);
    }

    /**
     * @param $sheetKey
     * @param $worksheetId
     * @return $this
     */
    public function setTarget($sheetKey, $worksheetId)
    {
        $this->setSheetKey($sheetKey)
             ->setWorksheetId($worksheetId);
        return $this;
    }

    /**
     * @return SpreadSheetsReader
     */
    public function getReader()
    {
        return new SpreadSheetsReader($this);
    }

    /**
     * @return SpreadSheetsWriter
     */
    public function getWriter()
    {
        return new SpreadSheetsWriter($this);
    }

    /**
     * @param string $sheetKey
     * @return Documents
     */
    public function getDocuments($sheetKey = '')
    {
        return new Documents($this, $sheetKey);
    }

    /**
     * @return ZendSpreadSheets
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return string
     */
    public function getSheetKey()
    {
        return $this->sheetKey;
    }

    /**
     * @param $sheetKey
     * @return $this
     */
    public function setSheetKey($sheetKey)
    {
        $this->sheetKey = $sheetKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getWorksheetId()
    {
        return $this->worksheetId;
    }

    /**
     * @param $worksheetId
     * @return $this
     */
    public function setWorksheetId($worksheetId)
    {
        $this->worksheetId = $worksheetId;
        return $this;
    }

    /**
     * @return \ZendGData\SpreadSheets\ListFeed
     */
    public function getListFeed()
    {
        $service = $this->getService();
        $query = new ZendSpreadSheets\ListQuery();
        $query->setSpreadsheetKey($this->getSheetKey())
              ->setWorksheetId($this->getWorksheetId());
        return $service->getListFeed($query);
    }


    /**
     * @throws \ZendGData\App\AuthException
     * @throws \ZendGData\App\CaptchaRequiredException
     * @throws \ZendGData\App\HttpException
     */
    protected function initSpreadSheetsService()
    {
        $user     = $this->user;
        $password = $this->password;
        $serviceName = $this->getServiceName();
        $httpClient  = $this->getHttpClient();
        $client = ClientLogin::getHttpClient($user, $password, $serviceName, $httpClient);
        $this->service = new ZendSpreadSheets($client);
    }

    /**
     * @return string
     */
    protected function getServiceName()
    {
        return ZendSpreadsheets::AUTH_SERVICE_NAME;
    }

    /**
     * @return HttpClient
     */
    protected function getHttpClient()
    {
        $adapter = new Curl();
        $adapter->setCurlOption(CURLOPT_SSL_VERIFYHOST, false)
                ->setCurlOption(CURLOPT_SSL_VERIFYPEER, false);
        $httpClient = new HttpClient();
        $httpClient->setAdapter($adapter);
        return $httpClient;
    }

    /**
     * @param array $user
     * @return bool
     */
    protected function validateUserArray(array $user)
    {
        $allGreen = true;
        foreach ($this->userDefinition as $def) {
            if (!array_key_exists($def, $user) && strlen($user[$def]) > 0) {
                $allGreen = false;
            }
        }
        return $allGreen;
    }


}
