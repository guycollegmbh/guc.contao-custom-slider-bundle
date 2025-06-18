<?php

namespace GUYCOLLEGMBH\ContaoCustomSliderBundle\Module;

use Contao\Module;
use Contao\BackendTemplate;
use Contao\Database;

class CustomSliderModule extends Module
{
    /**
     * @var string
     */
    protected $strTemplate = 'mod_customSlider';

    /**
     * Displays a wildcard in the back end.
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {
            $template = new BackendTemplate('be_wildcard');

            $template->wildcard = '### '.utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['customSlider'][0]).' ###';
            $template->title = $this->headline;
            $template->id = $this->id;
            $template->link = $this->name;
            $template->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id='.$this->id;

            return $template->parse();
        }

        return parent::generate();
    }

    /**
     * Generates the module.
     */
    protected function compile()
    {
        //$this->Template->message = 'Hello World';
        $objData = Database::getInstance()->prepare("SELECT * FROM tl_customslider ORDER BY sliderReihenfolge")->execute();
        $this->Template->slider = $objData->fetchAllAssoc();
    }
}