<?php


use Google\Cloud\BigQuery\BigQueryClient;
class BigQueryClientFactory
{
    public static function build($config = []) : BigQueryClient
    {
        $projectId  = 'z-web-services';     // todo: abstract this out to a .env config file
        $keyFilePath =  __DIR__ . '/keyFile.json';  // todo: abstract this out to a .env config file

        $config = array_merge([
            'keyFilePath' => $keyFilePath,
            'projectId' => $projectId,
        ], $config);

        $bigQuery = new BigQueryClient($config);
        return $bigQuery;
    }
}

