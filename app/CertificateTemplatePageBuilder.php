<?php

namespace App;

class CertificateTemplatePageBuilder
{
    /**
     * @var CertificateTemplatePage
     * $certificateTemplatePage
     */
    private $certificateTemplatePage;

    public function __construct()
    {
        $this->reset();
    }

    public function reset()
    {
        $this->certificateTemplatePage = new CertificateTemplatePage();
    }

    /**
     * @return CertificateTemplatePage
     */
    public function getResult()
    {
        $result = $this->certificateTemplatePage;
        $this->reset();
        return $result;
    }

    /**
     * @param $pagePayload
     */
    public function buildPlaceholders($pagePayload)
    {
        $placeholders = array_map(function($field) {
            return $field['placeholder'];
        }, $pagePayload['pagePlaceholdersAndValues']);
        $this->certificateTemplatePage->setPlaceholders($placeholders);
    }

    public function buildValues($pagePayload)
    {
        $values = array_map(function($field) {
            return $field['value'];
        }, $pagePayload['pagePlaceholdersAndValues']);
        $this->certificateTemplatePage->setValues($values);
    }

    public function buildFields($pagePayload)
    {
        $fields = $pagePayload['pageTemplate']['fields'];
        $this->certificateTemplatePage->setFields($fields);
    }
}
