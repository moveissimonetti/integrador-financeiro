<?php
namespace SonnyBlaine\Integrator\Destination;

use SonnyBlaine\Integrator\ConnectionManager;
use SonnyBlaine\Integrator\Source;

/**
 * Class RequestCreator
 * @package SonnyBlaine\Integrator\Destination
 */
class RequestCreator
{
    /**
     * Class to manage connection
     * @var ConnectionManager
     */
    protected $connectionManager;

    /**
     * RequestCreator constructor.
     * @param ConnectionManager $connectionManager Class to manage connection
     */
    public function __construct(ConnectionManager $connectionManager)
    {
        $this->connectionManager = $connectionManager;
    }

    /**
     * @param Source\Request $sourceRequest Request Source
     * @return array
     */
    public function create(Source\Request $sourceRequest)
    {
        $requests = [];

        $dataList = $this->fetchDataList($sourceRequest);

        foreach ($sourceRequest->getDestinations() as $destination) {
            $dataObject = $this->createDataObject($destination, $dataList);

            $requests[] = new Request($destination, $sourceRequest, $dataObject);
        }

        return $requests;
    }

    /**
     * @param Source\Request $sourceRequest Request Source
     * @return array
     * @throws \Exception
     */
    protected function fetchDataList(Source\Request $sourceRequest): array
    {
        $connection = $this->connectionManager->getConnection($sourceRequest->getConnection());
        $dataList = $connection->fetchAll($sourceRequest->getSql())[0];

        if (empty($dataList)) {
            throw new \Exception("No records found in the database to compose the request.");
        }

        return $dataList;
    }

    /**
     * @param Source\Destination $sourceDestination Destination of Source
     * @param array $dataList Array with data
     * @return \stdClass
     */
    protected function createDataObject(Source\Destination $sourceDestination, array $dataList): \stdClass
    {
        $dataList2Object = [];

        foreach ($dataList as $key => $dataValue) {
            $dataList2Object[$sourceDestination->getColumnByKey($key)] = $dataValue;
        }

        return (object)$dataList2Object;
    }
}