<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once "dompdf/autoload.inc.php";
use Dompdf\Dompdf;
function pdf_create($html, $filename='', $stream=TRUE) {
    $dompdf = new Dompdf();
    $dompdf->load_html($html);
    $dompdf->render();
    if ($stream) {
        $dompdf->stream($filename.".pdf");
    } else {
        return $dompdf->output();
    }
}
?>