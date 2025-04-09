<?php

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Entity\File\File as FileEntity;
use Concrete\Core\Entity\File\Version;
use Concrete\Core\File\File;
use Concrete\Core\Page\Page;
use HtmlObject\Element;

/** @var array $items */
/** @var int $timeout */
/** @var int $speed */

$c = Page::getCurrentPage();

?>

<?php if (is_object($c) && $c->isEditMode()) { ?>
    <div class="ccm-edit-mode-disabled-item">
        <div style="padding: 8px;">
            <?php echo t('Content disabled in edit mode.'); ?>
        </div>
    </div>
<?php } else { ?>
    <div>
        <div class="multimedia-slideshow" data-timeout="<?php echo h($timeout); ?>"
             data-speed="<?php echo h($speed); ?>">

            <?php if (isset($items) && is_array($items) && count($items) > 0) {
                foreach ($items as $item) {
                    if ($item["mediaType"] === "video") {
                        $mimeTypeMapping = [
                            "webmfID" => "video/webm",
                            "oggfID" => "video/ogg",
                            "mp4fID" => "video/mp4",
                        ];

                        $slideElement = new Element("div");
                        $slideElement->addClass("slide");
                        $videoElement = new Element("video");
                        $videoElement->setAttribute("muted", "muted");
                        $videoElement->setAttribute("playsinline", "playsinline");

                        foreach ($mimeTypeMapping as $fieldName => $mimeType) {
                            if (isset($item[$fieldName]) && !empty($item[$fieldName])) {
                                $fileEntity = File::getByID($item[$fieldName]);

                                if ($fileEntity instanceof FileEntity) {
                                    $fileVersionEntity = $fileEntity->getApprovedVersion();

                                    if ($fileVersionEntity instanceof Version) {
                                        $sourceElement = new Element("source");
                                        $sourceElement->setAttribute("src", $fileVersionEntity->getURL());
                                        $sourceElement->setAttribute("type", $mimeType);
                                        $videoElement->appendChild($sourceElement);
                                    }
                                }
                            }
                        }

                        $slideElement->appendChild($videoElement);

                        echo $slideElement->render();
                    } else {
                        $fileEntity = File::getByID($item["imagefID"]);

                        if ($fileEntity instanceof FileEntity) {
                            $fileVersionEntity = $fileEntity->getApprovedVersion();

                            if ($fileVersionEntity instanceof Version) {
                                $imageElement = new Element("img");
                                $imageElement->setAttribute("src", $fileVersionEntity->getURL());
                                $imageElement->addClass("slide");
                                echo $imageElement->render();
                            }
                        }
                    }
                }
            }
            ?>
        </div>
    </div>
<?php } ?>