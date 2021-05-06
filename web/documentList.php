<?php

require_once("../conf/bootstrap.php");

//читаем данные и HTTP-запроса, строим из них XML по схеме
$hreq = new HTTP_Request2Xml("schemas/TestApp/DocumentListRequest.xsd");
$req=new TestApp_DocumentListRequest();
if (!$hreq->isEmpty()) {
	$hreq->validate();
	$req->fromXmlStr($hreq->getAsXML());
}


// формируем xml-ответ
$xw = new XMLWriter();

//указать, в каком виде формировать отчет (html/pdf)
$formReport = $req->formReport; //узнать, в каком виде формировать отчет
if ($formReport == "pdf")
    $formReportOutput = new TestApp_FormReportPdf($req, $xw);
else
    $formReportOutput = new TestApp_FormReportHtml($req, $xw);


$formReportOutput->toXmlWriterInitial();
$formReportOutput->toXmlWriterHeaders();

// Если есть входные данные, проведем вычисления и выдадим ответ
if (!$hreq->isEmpty()) {
	$pdo=new PDO("mysql:host=localhost;dbname=testapp","testapp","1qazxsw2",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	//prior to PHP 5.3.6, the charset option was ignored. If you're running an older version of PHP, you must do it like this:
	//$pdo->exec("set names utf8");
    $displayName = "%".$req->displayName."%";
    $formReport = $req->formReport;
    $query = "SELECT * FROM document WHERE docDate BETWEEN :dateStart AND :dateEnd AND  displayName LIKE '" . $displayName. "'";
	$sth=$pdo->prepare($query);
	$sth->execute(array(":dateStart"=>$req->dateStart,":dateEnd"=>$req->dateEnd));
    //$sth->execute(array(":dateStart"=>$req->dateStart,":dateEnd"=>$req->dateEnd));
	while($row=$sth->fetch(PDO::FETCH_ASSOC)) {
        $formReportOutput->toXmlWriterDocs($row);
	}
}

$formReportOutput->toXmlWriterEnd();
$formReportOutput-> toXmlWriterFlush();
