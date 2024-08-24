<?php

namespace Concrete\Package\MultimediaSlideshow\Block\MultimediaSlideshow;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Error\ErrorList\ErrorList;

class Controller extends BlockController
{
    protected $btTable = 'btMultimediaSlideshow';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btCacheBlockOutputLifetime = 300;

    public function getBlockTypeDescription()
    {
        return t('Add support to add video and image slideshow to your site.');
    }

    public function getBlockTypeName()
    {
        return t("Multimedia Slideshow");
    }

    public function view()
    {
        /** @var Connection $db */
        $db = $this->app->make(Connection::class);
        $this->set("items", $db->fetchAll("SELECT * FROM btMultimediaSlideshowItems WHERE bID = ?", [$this->bID]));
    }

    public function add()
    {
        $this->set("items", []);
        $this->set("selector", "body");
        $this->set("timeout", 7000);
        $this->set("speed", 1500);
        $this->requireAsset('ckeditor');
    }

    public function edit()
    {
        /** @var Connection $db */
        $db = $this->app->make(Connection::class);
        $this->set("items", $db->fetchAll("SELECT * FROM btMultimediaSlideshowItems WHERE bID = ?", [$this->bID]));
        $this->requireAsset('ckeditor');
    }

    public function delete()
    {
        /** @var Connection $db */
        $db = $this->app->make(Connection::class);
        $db->executeQuery("DELETE FROM btMultimediaSlideshowItems WHERE bID = ?", [$this->bID]);

        parent::delete();
    }

    public function save($args)
    {
        parent::save($args);

        /** @var Connection $db */
        $db = $this->app->make(Connection::class);
        $db->executeQuery("DELETE FROM btMultimediaSlideshowItems WHERE bID = ?", [$this->bID]);

        if (is_array($args["items"])) {
            foreach ($args["items"] as $item) {
                $db->executeQuery("INSERT INTO btMultimediaSlideshowItems (bID, mediaType, description, imagefID, webmfID, oggfID, mp4fID) VALUES (?, ?, ?, ?, ?, ?, ?)", [
                    $this->bID,
                    isset($item["mediaType"]) && !empty($item["mediaType"]) ? $item["mediaType"] : "image",
                    isset($item["description"]) && !empty($item["description"]) ? $item["description"] : "",
                    isset($item["imagefID"]) && !empty($item["imagefID"]) ? $item["imagefID"] : null,
                    isset($item["webmfID"]) && !empty($item["webmfID"]) ? $item["webmfID"] : null,
                    isset($item["oggfID"]) && !empty($item["oggfID"]) ? $item["oggfID"] : null,
                    isset($item["mp4fID"]) && !empty($item["mp4fID"]) ? $item["mp4fID"] : null
                ]);
            }
        }
    }

    public function validate($args)
    {
        $e = new ErrorList;

        if (!isset($args["selector"]) || empty($args["selector"])) {
            $e->addError("You need to enter a valid CSS selector.");
        }

        if (!isset($args["timeout"]) || empty($args["timeout"])) {
            $e->addError("You need to enter a valid timeout value.");
        }

        if (!isset($args["speed"]) || empty($args["speed"])) {
            $e->addError("You need to enter a valid speed value.");
        }

        if (isset($args["items"])) {
            foreach($args["items"] as $item) {
                if (isset($item["mediaType"]) && !empty($item["mediaType"])) {
                    if ($item["mediaType"] === "image") {
                        if (!isset($item["imagefID"]) || empty($item["imagefID"])) {
                            $e->addError("You need to select a valid image file.");
                        }
                    } else if ($item["mediaType"] === "video") {
                        $videoFileAvailable = false;
                        $videoFileFields = ["webmfID", "oggfID", "mp4fID"];

                        foreach($videoFileFields as $videoFileField) {
                            if (isset($item[$videoFileField]) && !empty($item[$videoFileField])) {
                                $videoFileAvailable = true;
                            }
                        }

                        if (!$videoFileAvailable) {
                            $e->addError("You need to select a valid video file.");
                        }
                        
                    } else { 
                        $e->addError("You need to select a valid media type.");
                    }
                } else {
                    $e->addError("You need to select a valid media type.");
                }
            }
        } else {
            $e->addError("You need to add at least one item.");
        }
        
        return $e;
    }

    public function duplicate($newBID)
    {
        parent::duplicate($newBID);

        /** @var Connection $db */
        $db = $this->app->make(Connection::class);

        $copyFields = 'mediaType, imagefID, webmfID, oggfID, mp4fID, description';
        
        $db->executeUpdate("INSERT INTO btMultimediaSlideshowItems (bID, {$copyFields}) SELECT ?, {$copyFields} FROM btMultimediaSlideshowItems WHERE bID = ?", [
                $newBID,
                $this->bID
            ]
        );
    }
}