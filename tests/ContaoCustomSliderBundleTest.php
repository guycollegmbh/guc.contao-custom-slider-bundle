<?php

/*
 * This file is part of Contao Custom Slider Bundle.
 *
 * (c) John Doe
 *
 * @license LGPL-3.0-or-later
 */

namespace GUYCOLLEGMBH\ContaoCustomSliderBundle\Tests;

use GUYCOLLEGMBH\ContaoCustomSliderBundle\ContaoCustomSliderBundle;
use PHPUnit\Framework\TestCase;

class ContaoCustomSliderBundleTest extends TestCase
{
    public function testCanBeInstantiated()
    {
        $bundle = new ContaoCustomSliderBundle();

        $this->assertInstanceOf('GUYCOLLEGMBH\ContaoCustomSliderBundle\ContaoCustomSliderBundle', $bundle);
    }
}
