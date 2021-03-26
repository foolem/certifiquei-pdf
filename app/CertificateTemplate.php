<?php

namespace App;

use Exception;

/**
 * Class CertificateTemplate
 * @package App
 */
class CertificateTemplate
{
    const BACKGROUND_IMAGE_RESIZE = 6;
    const PIXEL_TO_MILLIMETER_CONVERSION_FACTOR = 0.3527;
    const MAXIMUM_HEIGHT = 40;

    /**
     * @var PDFEngineInterface
     * $pdfEngine
     */
    private $PDFEngine;
    private $pages;
    private $assetsDirectory;

    public function __construct()
    {
        $this->assetsDirectory = config('app.certificateAssetsDirectory');
    }

    /**
     * @param string $filePath
     * @throws Exception
     */
    public function output(string $filePath): void
    {
        try {
            $this->PDFEngine->output($filePath);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function generatePdf(): void
    {
        try {
            $pageCount = 0;
            foreach($this->getPages() as $page) {
                if ($pageCount > 0) {
                    $this->PDFEngine->addPage();
                }
                foreach ($page->getFields() as $field) {
                    $page->pushHTMLBlock($this->makeHTMLBlock($field));
                }
                $HTMLBlocks = $page->replacePlaceholdersByValues();
                $this->PDFEngine->writeHTML($HTMLBlocks);
                $pageCount++;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param PDFEngineInterface $PDFEngine
     * @return CertificateTemplate
     */
    public function setPDFEngine(PDFEngineInterface $PDFEngine): CertificateTemplate
    {
        $this->PDFEngine = $PDFEngine;
        return $this;
    }

    /**
     * @return PDFEngineInterface
     */
    public function getPDFEngine()
    {
        return $this->PDFEngine;
    }

    /**
     * @param $propertyInPixels
     * @return string
     */
    private function convertFromPixelsToMillimeters($propertyInPixels)
    {
        $CONVERSION_FACTOR = self::PIXEL_TO_MILLIMETER_CONVERSION_FACTOR;
        $numberOfPixels = substr($propertyInPixels, 0, -2);
        $propertyInMillimeters = (int)($CONVERSION_FACTOR * $numberOfPixels);
        return $propertyInMillimeters . 'mm';
    }

    /**
     * @param array $field
     * @return string
     */
    private function makeBlockContent(array $field): string
    {
        $blockContent = null;

        if (array_key_exists('value', $field)) {
            $blockContent .= $field['value'];
        }

        if (array_key_exists('img', $field)) {
            $blockContent .= $this->getImgTag($field['img']);
        }

        return $blockContent;
    }

    /**
     * @param array $field
     * @return string
     */
    private function makeHTMLBlock(array $field): string
    {
        $newClass = null;

        if (array_key_exists('divider', $field)) {
            $newClass = $this->calculateMarginTop($field, true) === 0
                ? 'divider-bottom'
                : 'divider-top';
        }

        // TODO: this string could be mounted in a better way
        return '
            <div class="item-certificate '
            . $newClass
            . '" '
            . $this->makeBlockStyle($field)
            . '>'
            . $this->makeBlockContent($field)
            . '</div>
        ';
    }

    /**
     * @param array $field
     * @param string $coordinate
     * @return float
     */
    private function getPosition(array $field, string $coordinate): float
    {
        return $field['position'][$coordinate];
    }

    /**
     * @param array $field
     * @return int
     */
    private function getHeight(array $field): int
    {
        return array_key_exists('height', $field)
            ? $field['height']
            : 0;
    }

    /**
     * @param array $field
     * @return string
     */
    private function makeBlockStyle(array $field): string
    {
        $CSSString = null;
        $styles = $field['styles'];
        foreach ($styles as $key => $value) {
            $value = $key === 'width'
                ? $this->convertFromPixelsToMillimeters($value)
                : $value;
            $CSSString .= $key . ':' . $value . ';';
        }
        $CSSString .= "margin-top: " . $this->calculateMarginTop($field, false) . "mm; ";
        $CSSString .= "left: " . $this->calculateMarginLeft($field) . "mm; ";
        $CSSString .= "position: absolute; ";

        return ' style="' . $CSSString . '"';
    }

    /**
     * @param array $field
     * @param bool $divider
     * @return int
     */
    private function calculateMarginTop(array $field, bool $divider): int
    {
        $field = (array_key_exists('divider', $field) && $divider)
            ? $field['divider']
            : $field;
        $heightInPixels = ($this->getHeight($field) > self::MAXIMUM_HEIGHT)
            ? self::MAXIMUM_HEIGHT
            : $this->getHeight($field);
        $marginTopInPixels = ($this->getPosition($field, 'y') - $heightInPixels);

        return (int)($marginTopInPixels * self::PIXEL_TO_MILLIMETER_CONVERSION_FACTOR);
    }

    /**
     * @param array $field
     * @return int
     */
    private function calculateMarginLeft(array $field): int
    {
        return (int)($this->getPosition($field, 'x') * self::PIXEL_TO_MILLIMETER_CONVERSION_FACTOR);
    }

    /**
     * @param string $url
     * @return string
     */
    private function getImgTag(string $url): string
    {
        return '<img src="' . $url . '"/>';
    }

    /**
     * @return mixed
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @param mixed $pages
     */
    public function setPages($pages): void
    {
        $this->pages = $pages;
    }
}
