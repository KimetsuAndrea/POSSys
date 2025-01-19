<?php
session_start();

// Ensure the session variable is set before using it
if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header("Location: index.php");
    exit;
}

session_regenerate_id(true);
include('includes/config.php');

// Include PhpSpreadsheet library (make sure it's installed and included)
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Create a new spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set the header row for the spreadsheet
$sheet->setCellValue('A1', '#')
      ->setCellValue('B1', 'Name')
      ->setCellValue('C1', 'Email')
      ->setCellValue('D1', 'Gender')
      ->setCellValue('E1', 'Phone')
      ->setCellValue('F1', 'Designation');

// Apply styles to the header row
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '4CAF50'], // Green background
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000'], // Black borders
        ],
    ],
];
$sheet->getStyle('A1:F1')->applyFromArray($headerStyle);
$sheet->getRowDimension('1')->setRowHeight(20); // Set header row height

// Get user data
$sql = "SELECT * FROM users";
$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

// Populate the spreadsheet with user data
$row = 2;  // Start from the second row
foreach ($results as $cnt => $result) {
    $sheet->setCellValue('A' . $row, $cnt + 1)
          ->setCellValue('B' . $row, $result->name)
          ->setCellValue('C' . $row, $result->email)
          ->setCellValue('D' . $row, $result->gender)
          ->setCellValue('E' . $row, $result->mobile)
          ->setCellValue('F' . $row, $result->designation);
    $row++;
}

// Apply border and auto width to all columns
$dataStyle = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000'], // Black borders
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER, // Center horizontally
        'vertical' => Alignment::VERTICAL_CENTER,    // Center vertically
    ],
];
$sheet->getStyle('A1:F' . ($row - 1))->applyFromArray($dataStyle);

// Auto width for all columns
foreach (range('A', 'F') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

// Set the header for the file download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Users_list-report.xls"');
header('Cache-Control: max-age=0');

// Write the file to output
$writer = new Xls($spreadsheet);
$writer->save('php://output');
exit;
?>