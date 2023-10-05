<?php

class [ExcelTableViewer](https://www.google.com/search?q=ExcelTableViewer) {
    private $excelFile;

    public function __construct($excelFile) {
        $this->excelFile = $excelFile;
    }

    public function displayTable() {
        // Verificar si el archivo existe
        if (file_exists($this->excelFile)) {
            // Cargar la librería [PHPExcel](https://www.google.com/search?q=PHPExcel)
            require_once 'PHPExcel/Classes/PHPExcel.php';

            // Crear un objeto [PHPExcel](https://www.google.com/search?q=PHPExcel) para leer el archivo [Excel](https://www.google.com/search?q=Excel)
            $objPHPExcel = [PHPExcel_IOFactory](https://www.google.com/search?q=PHPExcel_IOFactory)::load($this->excelFile);

            // Obtener la primera hoja del archivo
            $sheet = $objPHPExcel->getActiveSheet();

            // Obtener los datos de la hoja y mostrarlos en una tabla
            echo '<table>';
            foreach ($sheet->getRowIterator() as $row) {
                echo '<tr>';
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                foreach ($cellIterator as $cell) {
                    echo '<td>' . $cell->getValue() . '</td>';
                }

                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo 'El archivo no existe.';
        }
    }
}

// Ejemplo de uso
$excelViewer = new [ExcelTableViewer](https://www.google.com/search?q=ExcelTableViewer)('ruta/al/archivo.xlsx');
$excelViewer->displayTable();

?>