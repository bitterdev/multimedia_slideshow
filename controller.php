<?php

namespace Concrete\Package\MultimediaSlideshow;

use Concrete\Core\Package\Package;

class Controller extends Package
{
    protected $pkgHandle = 'multimedia_slideshow';
    protected $pkgVersion = '1.0.2';
    protected $appVersionRequired = '9.0.0';
    
    public function getPackageDescription()
    {
        return t('Add support to add video and image slideshow to your site.');
    }

    public function getPackageName()
    {
        return t('Multimedia Slideshow');
    }

    public function install()
    {
        $pkg = parent::install();
        $this->installContentFile("data.xml");
        return $pkg;
    }

    public function upgrade()
    {
        parent::upgrade();
        $this->installContentFile("data.xml");
    }
}