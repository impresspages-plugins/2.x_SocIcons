# Social icons plugin

Configurable plugin for adding social icons to websites running on ImpressPages CMS.

## Features

This plugin supports uploading of bitmap and vector icon images.
A few default popular icons provided.

## Install

1. Upload the `navigation` folder to your website's `ip_plugins` folder.
2. Login to the administration area.
3. Go to `Developer -> Modules` tab, and press `Install` button.

## Usage

* Add the following line to your template's layout file:
```
 <?php echo $this->generateSlot('socialIcons'); ?>
```

* Enter your website's administration area, hover the pointer above social icons, and click `Edit` button.
* To disable / enable specific icon, click "power" button or click an icon image.
* Click `Upload` button to upload your icons. You can upload multiple icons at once.
* Enter URL addresses of your social networking websites.
* Drag icons to reorder.
* Change the icon size by dragging `Icon size` control if needed.
* When you are done, click `Confirm` button to update the social icon view and URL addresses.
* To remove specific icon, click `Remove` button. 
