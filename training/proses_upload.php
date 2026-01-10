<?php
include "../config/koneksi.php";

require '../vendor/phpspreadsheet/src/PhpSpreadsheet.php';
require '../vendor/phpspreadsheet/src/PhpSpreadsheet/Reader/Xlsx.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

if(isset($_POST['upload'])){
    $file = $_FILES['file_excel']['tmp_name'];

    $reader = new Xlsx();
    $spreadsheet = $reader->load($file);
    $sheet = $spreadsheet->getActiveSheet()->toArray();

    // mulai dari baris ke-2 (baris 1 = header)
    for($i=1; $i<count($sheet); $i++){
        $ipk        = $sheet[$i][0];
        $sks        = $sheet[$i][1];
        $kehadiran  = $sheet[$i][2];
        $nilai_mk   = $sheet[$i][3];
        $kerja      = $sheet[$i][4];
        $kelulusan  = $sheet[$i][5];

        mysqli_query($koneksi,"INSERT INTO training VALUES(
            NULL,
            '$ipk',
            '$sks',
            '$kehadiran',
            '$nilai_mk',
            '$kerja',
            '$kelulusan'
        )");
    }

    header("location:training.php");
}
?>
