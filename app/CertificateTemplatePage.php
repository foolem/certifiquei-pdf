<?php

namespace App;

class CertificateTemplatePage
{
    private $placeholders;
    private $values, $fields= [];
    private $HTMLBlocks = null;
    private $background;

    public function replacePlaceholdersByValues()
    {
        return str_replace(
            $this->getPlaceholders(),
            $this->getValues(),
            $this->getHTMLBlocks()
        );
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Setters
     * @param $htmlBlock
     */
    public function pushHTMLBlock($htmlBlock)
    {
        $this->HTMLBlocks .= $htmlBlock;
    }

    /**
     * @return mixed
     */
    public function getPlaceholders()
    {
        return $this->placeholders;
    }

    /**
     * @param mixed $placeholders
     */
    public function setPlaceholders($placeholders): void
    {
        $this->placeholders = $placeholders;
    }

    /**
     * @return mixed
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param mixed $values
     */
    public function setValues($values): void
    {
        $this->values = $values;
    }

    /**
     * @return null
     */
    public function getHTMLBlocks()
    {
        return $this->HTMLBlocks;
    }

    /**
     * @param null $HTMLBlocks
     */
    public function setHTMLBlocks($HTMLBlocks): void
    {
        $this->HTMLBlocks = $HTMLBlocks;
    }

    /**
     * @param array $fields
     */
    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    /**
     * @return mixed
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * @param mixed $background
     */
    public function setBackground($background): void
    {
        $this->background = $background;
    }
}
