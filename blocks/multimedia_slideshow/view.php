<?php

use Concrete\Core\Entity\File\File as FileEntity;
use Concrete\Core\Entity\File\Version;
use Concrete\Core\File\File;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Utility\Service\Identifier;
use HtmlObject\Element;

defined('C5_EXECUTE') or die('Access denied');

/** @var array $items */

$c = Page::getCurrentPage();
$app = Application::getFacadeApplication();
/** @var $idHelper Identifer */
$idHelper = $app->make(Identifier::class);
$slideshowId = "ccm-multimedia-slideshow" . $idHelper->getString();
?>

<?php if (is_object($c) && $c->isEditMode()) { ?>
	<div class="ccm-edit-mode-disabled-item">
		<div style="padding: 8px;">
            <?php echo t('Content disabled in edit mode.'); ?>
        </div>
	</div>
<?php } else { ?>
    <div class="d-none">
        <div id="<?php echo h($slideshowId); ?>">
            <?php 
            if (isset($items) && is_array($items) && count($items) > 0)  {
                foreach($items as $item) {
                    if ($item["mediaType"] === "video") { 
                        $mimeTypeMapping = [
                            "webmfID" => "video/webm",
                            "oggfID" => "video/ogg",
                            "mp4fID" => "video/mp4",
                        ];

                        $videoElement = new Element("video");
                        $videoElement->setAttribute("muted", "muted");
                        $videoElement->setAttribute("playsinline", "playsinline");
                        $videoElement->addClass("slide");

                        foreach($mimeTypeMapping as $fieldName => $mimeType) {
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

                        echo $videoElement->render();
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

    <style type="text/css">
    #<?php echo $slideshowId; ?> {
        position: fixed;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
        z-index: -1;
        overflow: hidden;
    }

    #<?php echo $slideshowId; ?> .slide {
        position: absolute;
        min-width: 100%; 
        min-height: 100%; 
        width: auto;
        height: auto;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    #<?php echo $slideshowId; ?> .slide.active,
    #<?php echo $slideshowId; ?> .slide.active {
        z-index: 3;
    }
    </style>

    <script>
        (function ($) {
            $(function(){
                $slideshowContainer = $("#<?php echo $slideshowId; ?>");
                $slideshowContainer.appendTo(window.document.body);

                displayNextSlide = function() {
                    var $active = $slideshowContainer.find('.slide.active');

                    if ($active.length === 0) {
                        $active = $slideshowContainer.find('.slide:first');
                    }
                    
                    var $next = ($active.next().length > 0) ? $active.next() : $slideshowContainer.find('.slide:first');

                    $next.css('z-index', 2);

                    $active.fadeOut(1500, function(){
                        $active.css('z-index', 1).show().removeClass('active');
                        $next.css('z-index', 3).addClass('active');
                    
                        if ($next.get(0).tagName === "VIDEO") {
                            $next.get(0).currentTime = 0;
                            $next.get(0).play();
                            $next.get(0).addEventListener('ended', function() {
                                console.log("ended");
                                displayNextSlide();
                            }, false);
                        } else {
                            setTimeout(displayNextSlide, 7000);
                        }
                    });
                };
                
                displayNextSlide();
            });
        })(jQuery);
    </script>
<?php } ?>