<?php
	$title = $_POST['title'];
    $tabla = $_POST['content'];

    $content ='
<page>
    		<h1>'.$title.'</h1>
    		'.$tabla.'
</page>
    ';



    require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
    $html2pdf = new HTML2PDF('P','A4','es');
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    $html2pdf->Output('example.pdf');
?>