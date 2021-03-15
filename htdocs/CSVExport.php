<?php

require_once("Hydrogen/clsDataSource.php");


if (!isset($_GET['id'])) {
    die ("Invalid input");
}

$SQLID = (int) $_GET['id'];
if (!$SQLID) {
    die ("Invalid value");
}

//http://code.iamkate.com/php/creating-downloadable-csv-files/

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// fetch the data
$sql="select sqltext from saved_sql where id=" . $SQLID;
$sqlstring=$dds->getString($sql);
$decodedSQL=str_replace('#SINGLEQUOT#',"'",$sqlstring);
$nothing=$dds->setMaxRecs(9999);
$result=$dds->setSQL($decodedSQL);

// output the column headings
fputcsv($output, $dds->getFieldNames());

// loop over the rows, outputting them
while ($row=$dds->GetNextRow()) fputcsv($output, $row);




?>