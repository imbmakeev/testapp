<?php
/* тип отчета (html/pdf) */
interface ITestApp_IFormReport
{
    public function toXmlWriterInitial();
    public function toXmlWriterHeaders();
    public function toXmlWriterDocs($row);
    public function toXmlWriterEnd();
    public function toXmlWriterFlush();

}