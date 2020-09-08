<?php


require 'vendor/autoload.php';

use Google\Cloud\BigQuery\BigQueryClient;

include "BigQueryClientFactory.php";

$projectId = 'z-web-services';
$datasetName = 'cap_vehicle_data';
$tableName = 'contacts';

$bigQuery = BigQueryClientFactory::build();

// Run a query and inspect the results.
$queryJobConfig = $bigQuery->query(
    "SELECT distinct first_name, phone_number FROM {$projectId}.{$datasetName}.{$tableName}"
);
$queryResults = $bigQuery->runQuery($queryJobConfig);

foreach ($queryResults as $row) {
    print_r($row);
}


