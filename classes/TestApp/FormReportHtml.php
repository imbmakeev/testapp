<?php

require_once("IFormReport.php");

/* Используется для вывода отчета в html версии*/
class TestApp_FormReportHtml implements ITestApp_IFormReport
{
    public $req; //TestApp_DocumentListRequest
    public $xw; //XMLWriter



    public  $piTarget = "xml-stylesheet";
    public  $piContent = "type=\"text/xsl\" href=\"stylesheets/TestApp/DocumentList.xsl\"";
    //public  $ElementNSUri =

    public function  __construct(TestApp_DocumentListRequest &$req, XMLWriter &$xw) {
        $this->req = $req;
        $this->xw = $xw;

    }

    public function toXmlWriterInitial()
    {
        $this->xw->openMemory();
        $this->xw->setIndent(TRUE);
    }

    public function toXmlWriterHeaders()
    {

        $this->xw->startDocument("1.0", "UTF-8");
        $this->xw->writePi($this->piTarget, $this->piContent);

        $this->xw->startElementNS(NULL, "DocumentListResponse", "urn:ru:ilb:meta:TestApp:DocumentListResponse");

        $this->req->toXmlWriter($this->xw);
    }

    public function toXmlWriterDocs($row)
    {
        $doc = new TestApp_Document();
        $doc->fromArray($row);
        $doc->toXmlWriter($this->xw);
    }

    public function toXmlWriterEnd()
    {
        $this->xw->endElement();
        $this->xw->endDocument();
//Вывод ответа клиенту
    }

    public function toXmlWriterFlush()
    {
        header("Content-Type: text/xml");
        echo $this->xw->flush();
    }
}