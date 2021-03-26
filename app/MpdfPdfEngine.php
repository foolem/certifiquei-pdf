<?php

namespace App;

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\MpdfException;

class MpdfPdfEngine implements PDFEngineInterface
{
    /**
     * @var Mpdf
     * $MPDF
     */
    private $mpdf;

    /**
     * MPDFPDFEngine constructor.
     * @throws MpdfException
     */
    public function __construct()
    {
        $this->mpdf = new Mpdf($this->getMPDFConfig());
        $this->mpdf->showImageErrors = true;
    }

    public function setDefaultBodyCSS(string $property, string $value)
    {
        $this->mpdf->SetDefaultBodyCSS($property, $value);
    }

    /**
     * @param string $filePath
     * @param string $destination
     * @throws MpdfException
     */
    public function output(string $filePath, string $destination = ''): void
    {
        $this->mpdf->Output($filePath, $destination);
    }

    /**
     * @param $HTMLBlock
     * @param int $mode
     * @throws MpdfException
     */
    public function writeHTML($HTMLBlock, $mode = HTMLParserMode::DEFAULT_MODE): void
    {
        $this->mpdf->WriteHTML($HTMLBlock, $mode);
    }

    public function addPage(): void
    {
        $this->mpdf->AddPage();
    }

    private function getMPDFConfig()
    {
        return [
            'format' => 'A4-L',
            'dpi' => 72,
            'fontDir' => $this->getFontDirectories(),
            'fontdata' => $this->getFontData(),
            'default_font' => 'roboto',
        ];
    }

    private function getFontData()
    {
        $mPdfFontVariables = new FontVariables();
        $defaultFontConfig = $mPdfFontVariables->getDefaults();
        return $defaultFontConfig['fontdata'] + [
                'montserrat' => [
                    'R' => 'Montserrat-Regular.ttf',
                    'B' => 'Montserrat-Bold.ttf',
                    'I' => 'Montserrat-Italic.ttf',
                ],
                'roboto' => [
                    'R' => 'Roboto-Regular.ttf',
                    'B' => 'Roboto-Bold.ttf',
                    'I' => 'Roboto-Italic.ttf',
                ]
            ];
    }

    private function getFontDirectories()
    {
        $mPdfConfigVariables = new ConfigVariables();
        $defaultConfig = $mPdfConfigVariables->getDefaults();
        $assetsDirectory = config('app.certificateAssetsDirectory');
        return array_merge(
            $defaultConfig['fontDir'],
            [
                $assetsDirectory . '/custom-fonts/montserrat',
                $assetsDirectory . '/custom-fonts/roboto',
            ]
        );
    }
}
