<?php
    $tabla = $_POST['content'];

    $content ="
    <style type='text/Css'>
	<!--
	table {
width: 100%;
border: 1px solid #cef;
text-align: left; }
th {
font-weight: bold;
background-color: #acf;
border-bottom: 1px solid #cef; }
td,th {
padding: 4px 5px; }

.odd {
background-color: #def; }
.odd td {
border-bottom: 1px solid #cef; }
    -->
</style>
	<page >
    <table>
    ".$tabla."
    </table>
    </page>
    ";



    require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
    $html2pdf = new HTML2PDF('P','A4','es');
    $html2pdf->WriteHTML($content);
    $html2pdf->Output('exemple.pdf');
?>
