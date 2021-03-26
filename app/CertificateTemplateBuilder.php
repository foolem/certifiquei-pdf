<?php

namespace App;
use Exception;
use Mpdf\HTMLParserMode;
use Mpdf\MpdfException;

class CertificateTemplateBuilder
{
    const MPDF = "MPDF";

    /**
     * @var CertificateTemplate
     * $certificateTemplate
     */
    private $certificateTemplate;

    /**
     * @var CertificateTemplatePageBuilder
     * $certificateTemplatePageBuilder
     */
    private $certificateTemplatePageBuilder;

    public function __construct(CertificateTemplatePageBuilder $certificateTemplatePageBuilder)
    {
        $this->certificateTemplatePageBuilder = $certificateTemplatePageBuilder;
        $this->reset();
    }

    public function reset()
    {
        $this->certificateTemplate = new CertificateTemplate();
    }

    public function getResult()
    {
        $result = $this->certificateTemplate;
        $this->reset();
        return $result;
    }

    /**
     * @param string $PDFEngine
     * @throws MpdfException
     * @throws Exception
     */
    public function buildPDFEngine(string $PDFEngine)
    {
        $this->certificateTemplate->setPDFEngine($this->PDFEngineFactory($PDFEngine));
    }

    public function buildPages($pages)
    {
        $certificateTemplatePages = array_map(function ($page) {
            $this->certificateTemplatePageBuilder->buildPlaceholders($page);
            $this->certificateTemplatePageBuilder->buildValues($page);
            $this->certificateTemplatePageBuilder->buildFields($page);
            return $this->certificateTemplatePageBuilder->getResult();
        }, $pages);
        $this->certificateTemplate->setPages($certificateTemplatePages);
    }

    /**
     * @param $PDFEngine
     * @return PDFEngineInterface
     * @throws MpdfException
     * @throws Exception
     */
    private function PDFEngineFactory(string $PDFEngine): PDFEngineInterface
    {
        switch ($PDFEngine) {
            case self::MPDF:
                return $this->makeMpdf();
                break;
            default:
                throw new Exception('PDF engine not implemented');
        }
    }

    /**
     * @throws MpdfException
     */
    private function makeMpdf(): MpdfPdfEngine
    {
        $mpdfPdfEngine = new MpdfPdfEngine();
        $backgroundBaseStyle = $this->getBaseBackgroundStyle();
        $mpdfPdfEngine->writeHTML($backgroundBaseStyle);
        $globalStyleSheet = $this->getGlobalStyleSheet();
        $mpdfPdfEngine->writeHTML($globalStyleSheet, HTMLParserMode::HEADER_CSS);

        return $mpdfPdfEngine;
    }

    /**
     * @return false|string
     */
    private function getGlobalStyleSheet()
    {
       return file_get_contents(config('app.certificateAssetsDirectory') . '/style-quill.css');
    }

    /**
     * @return string
     */
    private function getBaseBackgroundStyle()
    {
        /**
         * Disclaimer: $backgroundBaseStyle is not real CSS, it's a MPDF specific syntax.
         */
        return "
            <style>
                @page {
                    margin: 0mm;
                    margin-header: 0mm;
                    margin-footer: 0mm;
                }
            </style>
        ";
    }
}
