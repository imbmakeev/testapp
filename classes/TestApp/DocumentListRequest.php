<?php
/**
 * @version $Id: DocumentListRequest.php 91 2013-04-14 07:11:31Z slavb $
 */
/**
 * Входные данные тестового отчета
 * @xmlns urn:ru:ilb:meta:TestApp:DocumentListRequest
 * @xmlname DocumentListRequest
 * @codegen true
 */
class TestApp_DocumentListRequest extends Adaptor_XMLBase {
	/**
	 * Дата начала периода
	 *
	 * @var Basictypes_Date
	 */
	public $dateStart;
	/**
	 * Конец периода
	 *
	 * @var Basictypes_Date
	 */
	public $dateEnd;
    /**
     * Наименоваие
     *
     * @var string
     */
    public $displayName;
    /**
     * Форма отчета
     *
     * @var string
     */
    public $formReport;

	/**
	 * Формат вывода
	 *
	 * @var string
	 */
	public $outputFormat="html";

	public function  __construct() {
		$this->dateStart=new Basictypes_Date("2000-01-01");
		$this->dateEnd=new Basictypes_Date();
        $this->displayName="";
        $this->formReport="";
	}
	/**
	 * Вывод в XMLWriter
	 * @codegen true
	 * @param XMLWriter $xw
	 * @param string $xmlname Имя корневого узла
	 * @param int $mode
	 */
	public function toXmlWriter(XMLWriter &$xw,$xmlname=NULL,$xmlns=NULL,$mode=Adaptor_XML::ELEMENT){
		$xmlname=$xmlname?$xmlname:"DocumentListRequest";
		$xmlns=$xmlns?$xmlns:"urn:ru:ilb:meta:TestApp:DocumentListRequest";
		if ($mode&Adaptor_XML::STARTELEMENT) $xw->startElementNS(NULL,$xmlname,$xmlns);
			if($this->dateStart!==NULL) {$xw->writeElement("dateStart",$this->dateStart->LogicalToXSD());}
			if($this->dateEnd!==NULL) {$xw->writeElement("dateEnd",$this->dateEnd->LogicalToXSD());}
            if($this->displayName!==NULL) {$xw->writeElement("displayName",$this->displayName);}
            if($this->formReport!==NULL) {$xw->writeElement("formReport",$this->formReport);}
			if($this->outputFormat!==NULL) {$xw->writeElement("outputFormat",$this->outputFormat);}
		if ($mode&Adaptor_XML::ENDELEMENT) $xw->endElement();
	}
    /**
     * Вывод в XMLWriter для Pdf
     * @codegen true
     * @param XMLWriter $xw
     * @param string $xmlname Имя корневого узла
     * @param int $mode
     */
    public function toXmlWriterPdf(XMLWriter &$xw){
        $xw->startElement("DocumentListRequest");
        $xw->writeAttribute("dateStart", $this->dateStart);
        $xw->writeAttribute("dateEnd", $this->dateEnd);
        $xw->writeAttribute("displayName", $this->displayName);
        $xw->endElement();
    }
	/**
	 * Чтение из  XMLReader
	 * @codegen true
	 * @param XMLReader $xr
	 */
	public function fromXmlReader(XMLReader &$xr){
		while($xr->nodeType!=XMLReader::ELEMENT) $xr->read();
		$root=$xr->localName;
		if($xr->isEmptyElement) return $this;
		while($xr->read()){
			if($xr->nodeType==XMLReader::ELEMENT) {
				$xsinil=$xr->getAttributeNs("nil","http://www.w3.org/2001/XMLSchema-instance")=="true";
				switch($xr->localName){
					case "dateStart": $this->dateStart=$xsinil?NULL:new Basictypes_Date($xr->readString(),Adaptor_DataType::XSD); break;
					case "dateEnd": $this->dateEnd=$xsinil?NULL:new Basictypes_Date($xr->readString(),Adaptor_DataType::XSD); break;
					case "displayName": $this->displayName=$xsinil?NULL:$xr->readString(); break;
                    case "formReport": $this->formReport=$xsinil?NULL:$xr->readString(); break;
					case "outputFormat": $this->outputFormat=$xsinil?NULL:$xr->readString(); break;
				}
			}elseif($xr->nodeType==XMLReader::END_ELEMENT&&$root==$xr->localName){
				return;
			}
		}
		return $this;
	}

}
