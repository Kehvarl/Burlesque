<?php

namespace JBBCode;

require_once 'CodeDefinition.php';
require_once 'CodeDefinitionBuilder.php';
require_once 'CodeDefinitionSet.php';
require_once 'validators/CssColorValidator.php';
require_once 'validators/UrlValidator.php';

/**
 * Provides a default set of common bbcode definitions.
 *
 * @author jbowens
 */
class BasicCodeDefinitionSet implements CodeDefinitionSet
{

    /* The default code definitions in this set. */
    protected $definitions = array();

    /**
     * Constructs the default code definitions.
     */
    public function __construct()
    {
        /* [b] bold tag */
        $builder = new CodeDefinitionBuilder('b', '<strong>{param}</strong>');
        array_push($this->definitions, $builder->build());

        /* [i] italics tag */
        $builder = new CodeDefinitionBuilder('i', '<em>{param}</em>');
        array_push($this->definitions, $builder->build());

        /* [u] italics tag */
        $builder = new CodeDefinitionBuilder('u', '<u>{param}</u>');
        array_push($this->definitions, $builder->build());
        
        /* [s] strikethrough tag */
        $builder = new CodeDefinitionBuilder('s', '<s>{param}</s>');
        array_push($this->definitions, $builder->build());
        
        /* [spoil] spoiler tag */
        $builder = new CodeDefinitionBuilder('spoil', '<span class="spoiler"><span class="spoiler_title">SPOILER</span> {param}</span>');
        array_push($this->definitions, $builder->build());
        
        /* [spoil=title] spoiler tag */
        $builder = new CodeDefinitionBuilder('spoil', '<span class="spoiler"><span class="spoiler_title">{option}</span> {param}</span>');
        $builder->setUseOption(true);
        array_push($this->definitions, $builder->build());

        $urlValidator = new \JBBCode\validators\UrlValidator();

        /* [url] link tag */
        $builder = new CodeDefinitionBuilder('url', '<a href="{param}">{param}</a>');
        $builder->setParseContent(false)->setBodyValidator($urlValidator);
        array_push($this->definitions, $builder->build());

        /* [url=http://example.com] link tag */
        $builder = new CodeDefinitionBuilder('url', '<a href="{option}">{param}</a>');
        $builder->setUseOption(true)->setParseContent(true)->setOptionValidator($urlValidator);
        array_push($this->definitions, $builder->build());

        
        /* [img] image tag */
        //$builder = new CodeDefinitionBuilder('img', '<img src="{param}" />');
        //$builder->setUseOption(false)->setParseContent(false)->setBodyValidator($urlValidator);
        //array_push($this->definitions, $builder->build());

        /* [img=alt text] image tag */
        //$builder = new CodeDefinitionBuilder('img', '<img src="{param} alt="{option}" />');
        //$builder->setUseOption(true);
        //array_push($this->definitions, $builder->build());

        /* [color] color tag */
        $builder = new CodeDefinitionBuilder('color', '<span style="color: {option}">{param}</span>');
        $builder->setUseOption(true)->setOptionValidator(new \JBBCode\validators\CssColorValidator());
        array_push($this->definitions, $builder->build());
        
        /* [fieldset=legend] field tag */
        //$builder = new CodeDefinitionBuilder('fieldset', '<fieldset><legend>{option}</legend>{param}</fieldset>');
        //$builder->setUseOption(true)->setOptionValidator(new \JBBCode\validators\CssColorValidator());
        //array_push($this->definitions, $builder->build());
    }

    /**
     * Returns an array of the default code definitions.
     */
    public function getCodeDefinitions()
    {
        return $this->definitions;
    }

}