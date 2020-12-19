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
     * @return void
     */
    public function create(Source\Request $sourceRequest): void
    {
        $dataList = $this->fetchDataList($sourceRequest);

        foreach ($dataList as $data) {
            foreach ($sourceRequest->getDestinations() as $destination) {
                $dataObject = $this->createDataObject($destination, $data);

                $sourceRequest->addDestinationRequest(
                    new Request($destination, $sourceRequest, $dataObject)
                );
            }
        }
    }

    /**
     * @param Source\Request $sourceRequest Request Source
     * @return array
     * @throws \Exception
     */
    protected function fetchDataList(Source\Request $sourceRequest): array
    {
        $connection = $this->connectionManager->getConnection($sourceRequest->getConnection());
        $dataList = $connection->fetchAll($sourceRequest->getSql(), ['param' => $sourceRequest->getQueryParameter()]);

        if (empty($dataList)) {
            throw new \Exception("No records found in the database to compose the request.");
        }

        if (!$sourceRequest->isAllowedMultipleResultset()) {
            return array_slice($dataList, 0, 1);
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
            $dataList2Object[$sourceDestination->getColumnByKey($key)] = mb_convert_encoding($dataValue, "UTF-8", mb_detect_encoding($dataValue, "UTF-8, ISO-8859-1, ISO-8859-15", true));
        }

        return (object)$dataList2Object;
    }
}