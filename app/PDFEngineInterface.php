<?php

namespace App;

use Exception;

interface PDFEngineInterface {

    public function addPage(): void;

    /**
     * @param string $filePath
     * @param string $destination
     * @throws Exception
     */
    public function output(string $filePath, string $destination = ''): void;

    /**
     * @param $HTMLBlock
     * @param int $mode
     * * @throws Exception
     */
    public function writeHTML($HTMLBlock, $mode = 0): void;

    /**
     * @param string $property
     * @param string $value
     * @return mixed
     */
    public function setDefaultBodyCSS(string $property, string $value);
}
