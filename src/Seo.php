<?php

namespace Src\Seo;

class SeoHelper
{
    private $html;

    public function __construct($html)
    {
        $this->html = $html;
    }

    /**
     * Return headLine by type.
     * @param string
     * @return string
     */
    public function getHeadline($headlineType)
    {
        if ($this->html->has($headlineType)) {
            $headLine = $this->html->find($headlineType)[0];
            $headLineText = $headLine->text();
            if (strlen($headLineText) > 255) {
                $headLineText = substr($headLineText, 0, 252) . '...';
            }
        } else {
            $headLineText = 'Empty';
        }
        return $headLineText;
    }

    /**
     * Return content from meta-tag by name.
     * @param string
     * @return string
     */
    public function getMetaContent($name)
    {
        if ($this->html->has("meta[name={$name}]")) {
            $data = $this->html->find("meta[name={$name}]::attr(content)")[0];
            if (strlen($data) > 255) {
                $data = substr($data, 0, 252) . '...';
            }
        } else {
            $data = 'Empty';
        }
        return $data;
    }
}
