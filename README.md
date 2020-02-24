## Introduction

**[Able Player](https://github.com/ableplayer/ableplayer)** is a fully
accessible cross-browser media player created by accessibility specialist
Terrill Thompson. It uses the HTML5 `<audio>` or `<video>` element for browsers that support them.

Details on the usage of the Able Player library, including up-to-date support
for filetypes and third-party media hosts, may be found at the [**Able Player
  GitHub page**](https://github.com/ableplayer/ableplayer).


#### Requirements

This module requires no modules outside of Drupal core.

This module requires the Media Drupal core module.


### Installation

  1. Download and enable the module using Composer, Drush, or file upload
  2. Enable the module either either through the Drupal admin
     (Extend › Media › Ableplayer) or Drush (`drush pm-enable ableplayer`)


### Configuration

  1. Create a Video Media type or edit an existing Video media type from
     Structure › Media › Add (`admin/structure/media/add`) and select Video file as the media source.
  2. Set the video file format to Ableplayer from Structure › Media › Videos
     › Manage display (`/admin/structure/media/manage/videos/display`) under the display column. Change this display to use Ableplayer.
  3. You can further modify the player behavior by selecting the 'Edit'
     button (shown as a gear icon while viewing the content). From here, you can configure whether or not a video loops or autoplays, and playback speed icons (up/down or hare/turtle).


### Usage

  1. Navigate to Content › Media and select the 'Add media' button.
  2. Upload your video file and associated captions, transcript, and description.
  3. Add a Media File Entity Reference field to an existing or new Entity Type (such as Content Type).
  4. Create a new Entity node using the configured Entity Type.
  5. Add your video file and publish the node.
  6. The page will render with the video shown through Able Player video wrapper.
