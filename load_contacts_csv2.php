<?php


require 'vendor/autoload.php';

use Google\Cloud\BigQuery\BigQueryClient;


$projectId = 'z-web-services';
$datasetName = 'cap_vehicle_data';
$tableName = 'contacts';
$keyFilePath =  __DIR__ . '/keyFile.json';
$csv =  __DIR__ . '/contacts.csv';

$bigQuery = new BigQueryClient([
    'keyFilePath' => $keyFilePath,
    'projectId' => $projectId
]);

// Get an instance of a previously created table.
$dataset = $bigQuery->dataset($datasetName);
$table = $dataset->table($tableName);

// Begin a job to import data from a CSV file into the table.
$loadJobConfig = $table->load(
    fopen($csv, 'r')
);
$job = $table->runJob($loadJobConfig);      // this will wait until complete.

// Run a query and inspect the results.
$queryJobConfig = $bigQuery->query(
    "SELECT distinct first_name, phone_number FROM {$projectId}.{$datasetName}.{$tableName}"
);
$queryResults = $bigQuery->runQuery($queryJobConfig);

foreach ($queryResults as $row) {
    print_r($row);
}


