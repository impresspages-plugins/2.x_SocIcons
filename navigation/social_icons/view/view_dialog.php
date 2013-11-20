<?php
if (!defined('CMS')) exit; //restrict direct access to file
?>
<div  class="ipSocIconsHeader">
    <div class="ipSocIconsUpload ipmControl">
       <a class="ipAdminButton ipaUpload" href="#" title="Upload icon">Upload</a>
    </div>
    <div class="ipSocIconsSliderContainer">
        <label class="ipAdminLabel">Icon size:</label>
        <div class="ipsSocialIconsSlider"></div>
    </div>
</div>
<ul id="ipsSocialIconsEdit" class="ipsSocialIconsEdit"
    data-width="<?php echo $this->escPar('navigation/social_icons/view/size') ?>"
    data-is_widget="<?php echo $this->esc('is_widget'); ?>">
    <?php
    $id = 0;
    foreach ($icons as $icon){
        ?>
        <li id="icon_<?php
        $id++;
        echo $id;
        ?>" draggable="true"
            data-filename="<?php
            echo $this->esc($icon['filename']);
            ?>"
            data-id="<?php
            echo $this->esc($icon['id']);
            ?>"
            data-enabled="<?php
            echo $this->esc($icon['enabled']);
            ?>">
                <div class="ipSocIconsFile">
                    <img
                        src="<?php

                        if ($icon['is_default']){
                            echo IP_SOCICONS_IMG_URL;
                        }else{
                            echo BASE_URL;
                        }

                        echo $this->esc($icon['filename']);

                        ?>"
                        height="<?php echo $this->esc($size); ?>"
                        title="Drag to reorder"
                        />
                </div>
            <div class="ipSocIconsUrl">
                <div>
                    <div class="ipSocIconsInput">
                        <input type="text" class="ipsSocialIconsLink" value="<?php
                        echo $this->esc($icon['url']);
                        ?>">
                    </div>
                    <div class="ipSocIconButtons">
                        <a href="#" id="#ipaFieldOnOff"><img src="<?php
                            echo IP_SOCICONS_IMG_URL.'onoff.png';
                            ?>" title="Disable/Enable"></a>
                        <?php
                        if (!$icon['is_default']){ ?>
                            <a href="#" class="ipaFieldRemove" data-id="<?php echo $this->esc($icon['id']); ?>"><img src="<?php
                                echo IP_SOCICONS_IMG_URL.'remove.png';
                                ?>" title="Remove"></a>
                        <?php
                        }
                        ?>

                    </div>
                </div>
            </div>
        </li>
    <?php
    }
    ?>
</ul>
<div class="ipSocIconsMainButtons">
    <a class="ipAdminButton ipaConfirm" href="#">Confirm</a>
    <a class="ipAdminButton ipaCancel" href="#">Cancel</a>
</div>