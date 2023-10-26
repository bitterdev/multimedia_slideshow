<?php

defined('C5_EXECUTE') or die('Access denied');

/** @var array $items */

?>

<div id="items-container"></div>

<script id="item-template" type="text/template">
    <div class="slideshow-item">
        <div class="form-group">
            <label for="mediaType-<%=id%>">
                <?php echo t("Type"); ?>
            </label>

            <select id="mediaType-<%=id%>" name="items[<%=id%>][mediaType]" class="form-control media-type-selector">
                <option value="image"<%=(mediaType !== 'video' ? " selected" : "")%>>
                    <?php echo t("Image"); ?>
                </option>

                <option value="video"<%=(mediaType === 'video' ? " selected" : "")%>>
                    <?php echo t("Video"); ?>
                </option>
            </select>
        </div>

        <div class="media-type image <%=(mediaType !== 'video' ? "" : " d-none")%>">
            <div class="form-group">
                <label for="imagefID-<%=id%>">
                    <?php echo t("Image"); ?>
                </label>

                <div id="imagefID-<%=id%>" data-concrete-file-input="imagefID-<%=id%>" class="file-selector">
                    <concrete-file-input 
                        <%=(imagefID !== null ? ":file-id=\"" + imagefID + "\"" : "")%>
                        choose-text="<?php echo t("Choose File");?>" 
                        input-name="items[<%=id%>][imagefID]">
                    </concrete-file-input>
                </div>
            </div>
        </div>
        
        <div class="media-type video <%=(mediaType === 'video' ? "" : " d-none")%>">
            <div class="form-group">
                <label for="webmfID-<%=id%>">
                    <?php echo t("WebM"); ?>
                </label>

                <div id="webmfID-<%=id%>" data-concrete-file-input="webmfID-<%=id%>" class="file-selector">
                    <concrete-file-input 
                        <%=(webmfID !== null ? ":file-id=\"" + webmfID + "\"" : "")%>
                        choose-text="<?php echo t("Choose File");?>" 
                        input-name="items[<%=id%>][webmfID]">
                    </concrete-file-input>
                </div>
            </div>

            <div class="form-group">
                <label for="oggfID-<%=id%>">
                    <?php echo t("Ogg"); ?>
                </label>

                <div id="oggfID-<%=id%>" data-concrete-file-input="oggfID-<%=id%>" class="file-selector">
                    <concrete-file-input 
                        <%=(oggfID !== null ? ":file-id=\"" + oggfID + "\"" : "")%>
                        choose-text="<?php echo t("Choose File");?>" 
                        input-name="items[<%=id%>][oggfID]">
                    </concrete-file-input>
                </div>
            </div>

            <div class="form-group">
                <label for="mp4fID-<%=id%>">
                    <?php echo t("MP4"); ?>
                </label>

                <div id="mp4fID-<%=id%>" data-concrete-file-input="mp4fID-<%=id%>" class="file-selector">
                    <concrete-file-input 
                        <%=(mp4fID !== null ? ":file-id=\"" + mp4fID + "\"" : "")%>
                        choose-text="<?php echo t("Choose File");?>" 
                        input-name="items[<%=id%>][mp4fID]">
                    </concrete-file-input>
                </div>
            </div>
        </div>

        <a href="javascript:void(0);" class="btn btn-danger">
            <?php echo t("Remove Item"); ?>
        </a>
    </div>
</script>

<a href="javascript:void(0);" class="btn btn-primary" id="ccm-add-item">
    <?php echo t("Add Item"); ?>
</a>

<style type="text/css">
    .slideshow-item {
        border: 1px solid #dadada;
        background: #f9f9f9;
        padding: 15px;
        margin-bottom: 15px;
    }
</style>

<script type="text/javascript">
    (function ($) {
        var nextInsertId = 0;
        var items = <?php echo json_encode($items);?>;

        var addItem = function (data) {
            var defaults = {
                id: nextInsertId
            };

            var combinedData = { ...defaults, ...data };

            var $item = $(_.template($("#item-template").html())(combinedData));

            nextInsertId++;

            $item.find(".btn-danger").click(function () {
                $(this).parent().remove();
            });

            Concrete.Vue.activateContext('cms', function (Vue, config) {
                $item.find(".file-selector").each(function() {
                    new Vue({
                        el: this,
                        components: config.components
                    });
                });
            });
            
            $item.find(".media-type-selector").change(function() {
                if ($(this).val() === "video") {
                    $(this).parent().parent().find(".media-type.image").addClass("d-none");
                    $(this).parent().parent().find(".media-type.video").removeClass("d-none");
                } else {
                    $(this).parent().parent().find(".media-type.image").removeClass("d-none");
                    $(this).parent().parent().find(".media-type.video").addClass("d-none");
                }
            });

            $("#items-container").append($item);
        };

        for (var item of items) {
            addItem(item);
        }

        $("#ccm-add-item").click(function (e) {
            e.preventDefault();
            addItem({
                mediaType: 'image',
                imagefID: null,
                webmfID: null,
                oggfID: null,
                mp4fID: null
            });
            return true;
        });
    })(jQuery);
</script>