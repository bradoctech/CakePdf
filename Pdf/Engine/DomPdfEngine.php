<?php
require APP . 'Vendor/autoload.php';

use Dompdf\Dompdf;

App::uses('AbstractPdfEngine', 'CakePdf.Pdf/Engine');

class DomPdfEngine extends AbstractPdfEngine {

/**
 * Constructor
 *
 * @param $Pdf CakePdf instance
 */
	public function __construct(CakePdf $Pdf) {
		parent::__construct($Pdf);
	}

/**
 * Generates Pdf from html
 *
 * @return string raw pdf data
 */
	public function output() {
		$DomPDF = new Dompdf();

		$this->loadOptions($DomPDF);

		$html = $this->fixEncoding();

		$DomPDF->set_paper($this->_Pdf->pageSize(), $this->_Pdf->orientation());
		$DomPDF->load_html($html);
		$DomPDF->render();

		return $DomPDF->output();
	}

	private function loadOptions($dompdf)
	{
		$options = $this->config('options');
		if (!is_array($options)) {
			return;
		}

		foreach ($options as $option => $value) {
			$dompdf->setOption($option, $value);
		}
	}

	private function fixEncoding() : string
	{
		$html = $this->_Pdf->html();

		$encoding = $this->config('encoding') ?? 'utf-8';
		if (strtolower($encoding) === 'iso-8859-1') {
			$html = utf8_encode($html);
		}

		return $html;
	}

}
