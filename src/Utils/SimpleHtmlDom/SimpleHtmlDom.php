<?php

namespace Learnosity\Utils\SimpleHtmlDom;

require_once "simple_html_dom.php";

/**
 * just a wrapper for simple_html_dom to deal with namespace in Slim
 */

class SimpleHtmlDom extends simple_html_dom
{
    public function getSelfClosingTags()
    {
        return $this->self_closing_tags;
    }
}
