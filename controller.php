<?php

namespace Concrete\Package\MultimediaSlideshow;

use Bitter\MultimediaSlideshow\Provider\ServiceProvider;
use Concrete\Core\Entity\Package as PackageEntity;
use Concrete\Core\Package\Package;

class Controller extends Package
{
    protected string $pkgHandle = 'multimedia_slideshow';
    protected string $pkgVersion = '1.0.4';
    protected $appVersionRequired = '9.0.0';
    protected $pkgAutoloaderRegistries = [
        'src/Bitter/MultimediaSlideshow' => 'Bitter\MultimediaSlideshow',
    ];

    public function getPackageDescription(): string
    {
        return t('Create responsive slideshows with images and videos, supporting multiple video formats.');
    }

    public function getPackageName(): string
    {
        return t('Multimedia Slideshow');
    }

    public function on_start()
    {
        /** @var ServiceProvider $serviceProvider */
        /** @noinspection PhpUnhandledExceptionInspection */
        $serviceProvider = $this->app->make(ServiceProvider::class);
        $serviceProvider->register();
    }

    public function install(): PackageEntity
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