<?php

// https://github.com/googleapis/google-cloud-php-bigquery
// https://cloud.google.com/bigquery/docs/loading-data-local

// https://cloud.google.com/bigquery/docs/loading-data-cloud-storage-json

require 'vendor/autoload.php';

use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\Core\ExponentialBackoff;

/** Uncomment and populate these variables in your code */
$projectId  = 'z-web-services';
$datasetId  = 'cap_vehicle_data';
$tableId    = 'contacts';
$source     = __DIR__ . '/contacts.ndjson';
$keyFilePath =  __DIR__ . '/keyFile.json';


// instantiate the bigquery table service
$bigQuery = new BigQueryClient([
    'keyFilePath' => $keyFilePath,
    'projectId' => $projectId,
]);
$dataset = $bigQuery->dataset($datasetId);
$table = $dataset->table($tableId);
// create the import job
$loadConfig = $table->load(fopen($source, 'r'))->sourceFormat('NEWLINE_DELIMITED_JSON');

$job = $table->runJob($loadConfig);
// poll the job until it is complete
$backoff = new ExponentialBackoff(10);
$backoff->execute(function () use ($job) {
    printf('Waiting for job to complete' . PHP_EOL);
    $job->reload();
    if (!$job->isComplete()) {
        throw new Exception('Job has not yet completed', 500);
    }
});
// check if the job has errors
if (isset($job->info()['status']['errorResult'])) {
    $error = $job->info()['status']['errorResult']['message'];
    printf('Error running job: %s' . PHP_EOL, $error);
    print_r($job->info()['status']);
} else {
    print('Data imported successfully' . PHP_EOL);
}