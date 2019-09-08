<?php

namespace Basilicom\ThumbnailBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class BasilicomThumbnailBundle extends AbstractPimcoreBundle
{
    public function getJsPaths()
    {
        return [
            '/bundles/basilicomthumbnail/js/pimcore/startup.js'
        ];
    }
}