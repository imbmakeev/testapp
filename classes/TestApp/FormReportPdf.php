<?php
require_once("IFormReport.php");
/* Используется для вывода отчета в html версии*/
class TestApp_FormReportPdf implements ITestApp_IFormReport
{
    public $req; //TestApp_DocumentListRequest
    public $xw; //XMLWriter

    public function __construct(TestApp_DocumentListRequest &$req, XMLWriter &$xw)
    {
        $this->req = $req;
        $this->xw = $xw;
    }

    public function toXmlWriterInitial()
    {
        $this->xw->openMemory(); //todo openURI
        //$this->xw->openURI('pdf.xml');
        $this->xw->setIndent(TRUE);
    }

    public function toXmlWriterHeaders()
    {

        $this->xw->startDocument("1.0", "UTF-8");

        $this->xw->startElementNS("documentList", "Envelope", "http://www.w3.org/1999/XSL/Transform");
        $this->xw->writeAttribute("xmlns", "http://www.w3.org/1999/XSL/Transform");

        $this->req->toXmlWriterPdf($this->xw);

    }

    public function toXmlWriterDocs($row)
    {
        $doc = new TestApp_Document();
        $doc->fromArray($row);
        $doc->toXmlWriterPdf($this->xw);
    }

    public function toXmlWriterEnd()
    {
        $this->xw->endElement();
        $this->xw->endDocument();
    }

    public function toXmlWriterFlush()
    {
        $headers = array();
        $output = null;
        $erofxref = new Eurofxref();
        $ratesxml = $this->xw->flush(false);

        $attachmentName = "Список документов.pdf";
        $headers = array(
            "Content-Type: application/pdf",
            "Content-Disposition: inline; filename*=UTF-8''" . $attachmentName
        );
        $fo = $erofxref->transformFo($ratesxml);
        $output = $erofxref->transformPdf($fo);

        foreach ($headers as $h) {
            header($h);
        }
        echo $output;
    }
}

class Eurofxref
{
    function getRates()
    {
        return file_get_contents("documentListToPdf3.xml");
    }

    function transformFo($xml)
    {
        $xmldom = new DOMDocument();
        $xmldom->loadXML($xml);
        $xsldom = new DomDocument();

        $filePath = "documentListToPdf.xsl";
        if (!file_exists($filePath))
            $filePath = "../classes/TestApp/" . $filePath;
        $xsldom->load($filePath);

        $proc = new XSLTProcessor();
        $proc->importStyleSheet($xsldom);
        $res = $proc->transformToXML($xmldom);
        return $res;
    }

    function transformPdf($xml)
    {
        $url = "https://demo01.ilb.ru/fopservlet/fopservlet";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/xml"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

        // для тестового задания отключил проверку ssl. На реальном проекте необходимо другое решение
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);


        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($code != 200) {
            throw new Exception($res . PHP_EOL . $url . " " . curl_error($ch), 450);
        }
        curl_close($ch);
        return $res;
    }
}