<?php

namespace App\Entity;

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CreateExcel
{

    protected $spreadsheet;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
    }

    public function createXlxs(
        $fileName = 'Statistiques.APIE.SIPIC.xlsx'
    ) {
        $sheet = $this->spreadsheet->getActiveSheet();
        ob_clean();
        $writer = new Xlsx($this->spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');
    }

    public function addData(array $data, $typeOfData = 'number', $startColumn, $startRow, string $columnName, array $headers = [])
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $borderStyle = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['argb' => '000000'], // Couleur noire
                ],
            ],
        ];

        //Stylisation des colonnes
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('E1:L1');
        $sheet->getDefaultColumnDimension()->setWidth(25);
        $sheet->getDefaultRowDimension()->setRowHeight(20);
        $sheet->getColumnDimension('B')->setWidth(34);
        $sheet->getColumnDimension('F')->setWidth(34);
        $sheet->getColumnDimension('L')->setWidth(34);
        $sheet->getStyle('B2:B' . $sheet->getHighestRow())->applyFromArray($borderStyle);
        $sheet->getStyle('F2:F' . $sheet->getHighestRow())->applyFromArray($borderStyle);
        $sheet->getStyle('L2:L' . $sheet->getHighestRow())->applyFromArray($borderStyle);

        // Appliquer la couleur de fond des colonnes
        $startRow = 2;

        // Rouge semi-transparent
        $sheet->getStyle('B' . $startRow . ':B' . $sheet->getHighestRow())
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF9179');

        // Vert semi-transparent
        $sheet->getStyle('F' . $startRow . ':F' . $sheet->getHighestRow())
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF92FF7E');

        // Jaune semi-transparent
        $sheet->getStyle('L' . $startRow . ':L' . $sheet->getHighestRow())
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FFF7FF7E');

        // Centrer horizontalement et verticalement tout le document
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow())
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // Mise en gras
        $sheet->getStyle('A1')
            ->getFont()
            ->setBold(true);

        $sheet->getStyle('E1')
            ->getFont()
            ->setBold(true);

        $sheet->getStyle('2:2')
            ->getFont()
            ->setBold(true);


        // Mise en place des données constantes
        $sheet->setCellValueByColumnAndRow(5, 1, 'Bon de livraison SAGE (APIE & SIPIC)');
        $sheet->setCellValueByColumnAndRow(1, 1, 'Préparation de livraison SAGE (APIE & SIPIC)');
        $sheet->setCellValueByColumnAndRow($startColumn, $startRow, $columnName);

        // Itération des tableaux pour définir le commencement de l'écriture
        for ($i = 0, $l = sizeof($headers); $i < $l; $i++) {
            $sheet->setCellValueByColumnAndRow($startColumn + $i - 1, $startRow + 1, $headers[$i]);
        }

        // Ecriture des données
        for ($i = 0, $l = sizeof($data); $i < $l; $i++) { // row $i
            $j = 0;
            foreach ($data[$i] as $k => $v) { // column $j
                if ($typeOfData == 'date' && $k === "VL_DODATELIVR") {
                    $sheet->setCellValueByColumnAndRow(1, $startRow + $i + 1, $v);
                } elseif ($k === "sum" && $typeOfData == 'number') {
                    $formattedNumber = (float)$v;
                    $sheet->setCellValueExplicitByColumnAndRow($startColumn + $j - 1, $startRow + $i + 1, $formattedNumber, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                    $sheet->getStyleByColumnAndRow($startColumn + $j - 1, $startRow + $i + 1)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.00 €');
                }

                $j++;
            }
        }
        return $this;
    }
}
