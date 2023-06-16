<?php

class CSVUtils
{
    private static string $separator = ';';
    private static string $enclosure = '"';
    private static string $escape = '\\';

    public static function filter(string $filePath, string $field, string $values) {
        $csvData = self::getDataFromCsvFile($filePath);
        $filteredData = array_filter($csvData, function ($row) use ($field, $values) {
            $values = explode(',', $values);
            return in_array($row->{$field}, $values);
        });
        return $filteredData;
    }

    public static function getDataFromCsvFile(string $filePath) {
        $fileArray = file($filePath);
        // $csvArray = array_map('str_getcsv', $fileArray);
        $csvArray = array_map(function($row) {
            return str_getcsv($row, self::$separator, self::$enclosure, self::$escape);
        }, $fileArray);

        $fields = null;
        $data = [];
        foreach($csvArray as $rowIndex => $row) {

            // Collect fields names
            if (empty($fields)) { $fields = $row; continue; }

            // Collect rows data
            $rowData = new stdClass();
            foreach($fields as $fieldIndex => $fieldName) {
                if (array_key_exists($fieldIndex, $row) === false) {
                    echo "\nField index: [{$fieldIndex}] not found at row index: [{$rowIndex}]";
                }
                $rowData->{$fieldName} = $row[$fieldIndex];
            }
            array_push($data, $rowData);
        }
        return $data;
    }
}
