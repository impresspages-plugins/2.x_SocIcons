<?php
if (!defined('CMS')) exit; //restrict direct access to file
?>
<div class="ipModuleInlineManagement ipmSocIcons">
    <div class="ipmSocIconsContainer">
        <ul>
        <?php
        foreach ($icons as $icon){
            if ($icon['enabled']){
                ?>
             <li>
                <a target="_blank" href="<?php echo $this->esc($icon['url']); ?>"><img src="<?php
                    echo $this->esc($icon['resized_filename']);
                ?>" height="<?php
                    echo $this->esc($size);
                ?>" width="auto" alt=""></a>
             </li>
        <?php
            }
        }
        ?>
        </ul>
    </div>
</div>
