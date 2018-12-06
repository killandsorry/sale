<?
include 'mpdf.php';
$pdf = new mPDF('utf-8', 'A4');
//$css = addStyle();
//$html = drupal_render(node_view($node));
$html = file_get_contents('temp.html');
//$pdf->WriteHTML($css ,1);
$pdf->WriteHTML($html, 2);
$pdf->Output('filename.pdf', 'F');
?>