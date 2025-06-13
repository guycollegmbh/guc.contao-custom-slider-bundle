<?php

/*
 * This file is part of Contao Custom Slider Bundle.
 *
 * (c) GUYCOLLE GMBH
 *
 * @license LGPL-3.0-or-later
 */

namespace GUYCOLLEGMBH\ContaoCustomSliderBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use GUYCOLLEGMBH\ContaoCustomSliderBundle\ContaoCustomSliderBundle;



class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(ContaoCustomSliderBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
