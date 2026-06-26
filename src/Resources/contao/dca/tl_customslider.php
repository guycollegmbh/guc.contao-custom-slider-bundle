<?php

use Contao\Backend;
use Contao\DataContainer;
use Contao\DC_Table;
use Contao\StringUtil;
use Contao\System;

$GLOBALS['TL_DCA']['tl_customslider'] = array
(
    // Config
    'config' => array
    (
        'dataContainer'    => DC_Table::class,
        'enableVersioning' => true,
        '__selector__'     => ['mediaType'],
        'sql'              => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        ),
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'        => 2,
            'fields'      => array('Bezeichnung'),
            'flag'        => 1,
            'panelLayout' => 'filter;sort,search,limit'
        ),
        'label' => array
        (
            'fields' => array('Bezeichnung', 'active'),
            'format' => '%s (Slide Aktiv: %s)',
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_customslider']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ),
            'delete' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['tl_customslider']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? '') . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['tl_customslider']['show'],
                'href'       => 'act=show',
                'icon'       => 'show.gif',
                'attributes' => 'style="margin-right:3px"'
            ),
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default' => 'Bezeichnung,alias,mediaType,sliderTitel,sliderUntertitel,sliderText,sliderColor,sliderLinkURL,target,sliderLinkText,sliderLinkTitle,sliderPlazierung,sliderReihenfolge,active'
    ),

    // Subpalettes
    'subpalettes' => array
    (
        'mediaType_image' => 'sliderBild',
        'mediaType_video' => 'sliderVimeoId',
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'sorting' => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'tstamp' => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'Bezeichnung' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_customslider']['Bezeichnung'],
            'inputType' => 'text',
            'exclude'   => true,
            'sorting'   => true,
            'flag'      => 1,
            'search'    => true,
            'eval'      => array('mandatory' => true, 'unique' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'alias' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_customslider']['alias'],
            'exclude'       => true,
            'search'        => true,
            'inputType'     => 'text',
            'eval'          => array('rgxp' => 'alias', 'doNotCopy' => true, 'unique' => true, 'maxlength' => 128, 'tl_class' => 'w50'),
            'save_callback' => array
            (
                array('tl_customslider', 'generateAlias')
            ),
            'sql'           => ['type' => 'string', 'length' => 255, 'default' => '']
        ),
        'mediaType' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_customslider']['mediaType'],
            'exclude'   => true,
            'inputType' => 'select',
            'options'   => ['image', 'video'],
            'reference' => &$GLOBALS['TL_LANG']['tl_customslider']['mediaTypeOptions'],
            'eval'      => array('submitOnChange' => true, 'tl_class' => 'w50'),
            'sql'       => "varchar(10) NOT NULL default 'image'"
        ),
        'sliderBild' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_customslider']['sliderBild'],
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => array('fieldType' => 'radio', 'filesOnly' => true, 'extensions' => \Contao\Config::get('validImageTypes')),
            'sql'       => "binary(16) NULL"
        ),
        'sliderVimeoId' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_customslider']['sliderVimeoId'],
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'eval'      => array('maxlength' => 20, 'rgxp' => 'digit', 'tl_class' => 'w50'),
            'sql'       => "varchar(20) NOT NULL default ''"
        ),
        'sliderTitel' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_customslider']['sliderTitel'],
            'inputType' => 'text',
            'exclude'   => true,
            'sorting'   => true,
            'flag'      => 1,
            'search'    => true,
            'eval'      => array('tl_class' => 'w50 clr'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'sliderUntertitel' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_customslider']['sliderUntertitel'],
            'inputType' => 'text',
            'exclude'   => true,
            'sorting'   => true,
            'flag'      => 1,
            'search'    => true,
            'eval'      => array('tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'sliderText' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_customslider']['sliderText'],
            'inputType' => 'text',
            'exclude'   => true,
            'sorting'   => true,
            'flag'      => 1,
            'search'    => true,
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'sliderColor' => array
        (
            'label'          => &$GLOBALS['TL_LANG']['tl_customslider']['sliderColor'],
            'inputType'      => 'text',
            'exclude'        => true,
            'search'         => true,
            'eval'           => array('maxlength' => 6, 'tl_class' => 'w50'),
            'save_callback'  => array
            (
                array('tl_customslider', 'validateColor')
            ),
            'sql'            => "varchar(6) NOT NULL default ''"
        ),
        'sliderLinkURL' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_customslider']['sliderLinkURL'],
            'exclude'   => true,
            'inputType' => 'pageTree',
            'eval'      => array('fieldType' => 'radio', 'tl_class' => 'clr'),
            'sql'       => "int(10) unsigned NOT NULL default '0'"
        ),
        'target' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_customslider']['target'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'sql'       => "char(1) NOT NULL default ''"
        ),
        'sliderLinkText' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_customslider']['sliderLinkText'],
            'inputType' => 'text',
            'exclude'   => true,
            'sorting'   => true,
            'flag'      => 1,
            'search'    => true,
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'sliderLinkTitle' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_customslider']['sliderLinkTitle'],
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'eval'      => array('maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'sliderPlazierung' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_customslider']['sliderPlazierung'],
            'exclude'   => true,
            'inputType' => 'pageTree',
            'eval'      => array('fieldType' => 'checkbox', 'tl_class' => 'clr', 'multiple' => true),
            'sql'       => "blob NULL"
        ),
        'sliderReihenfolge' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_customslider']['sliderReihenfolge'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array('maxlength' => 10, 'rgxp' => 'digit'),
            'sql'       => "int(10) unsigned NOT NULL default '0'"
        ),
        'active' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_customslider']['active'],
            'default'   => '1',
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => array('tl_class' => 'w50'),
            'sql'       => "char(1) NOT NULL default '1'"
        )
    )
);


class tl_customslider extends Backend
{
    public function generateAlias($varValue, DataContainer $dc)
    {
        $autoAlias = false;

        if ($varValue == '') {
            $autoAlias = true;
            $varValue = StringUtil::generateAlias($dc->activeRecord->Bezeichnung);
        }

        $connection = System::getContainer()->get('database_connection');
        $count = $connection->fetchOne(
            'SELECT COUNT(*) FROM tl_customslider WHERE alias=? AND id!=?',
            [$varValue, $dc->id]
        );

        if ($count > 0) {
            if (!$autoAlias) {
                throw new \Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
            }

            $varValue .= '-' . $dc->id;
        }

        return $varValue;
    }

    public function validateColor($varValue, DataContainer $dc)
    {
        if ($varValue !== '' && !preg_match('/^[0-9a-fA-F]{6}$/', $varValue)) {
            throw new \Exception($GLOBALS['TL_LANG']['tl_customslider']['colorInvalid'] ?? 'Bitte einen gültigen HEX-Farbwert eingeben (6 Zeichen ohne #, z.B. ff0000)');
        }

        return $varValue;
    }
}
