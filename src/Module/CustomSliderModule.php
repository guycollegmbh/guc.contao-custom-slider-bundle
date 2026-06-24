<?php

namespace GUYCOLLEGMBH\ContaoCustomSliderBundle\Module;

use Contao\BackendTemplate;
use Contao\Module;
use Contao\System;

class CustomSliderModule extends Module
{
    protected $strTemplate = 'mod_customSlider';

    public function generate()
    {
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();

        if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request)) {
            $template = new BackendTemplate('be_wildcard');

            $template->wildcard = '### '.mb_strtoupper($GLOBALS['TL_LANG']['FMD']['customSlider'][0]).' ###';
            $template->title = $this->headline;
            $template->id = $this->id;
            $template->link = $this->name;
            $template->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id='.$this->id;

            return $template->parse();
        }

        return parent::generate();
    }

    protected function compile()
    {
        $connection = System::getContainer()->get('database_connection');
        $result = $connection->executeQuery('SELECT * FROM tl_customslider ORDER BY sliderReihenfolge');
        $this->Template->slider = $result->fetchAllAssociative();
    }
}
