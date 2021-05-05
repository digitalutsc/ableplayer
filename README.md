## Introduction

**[Able Player](https://github.com/ableplayer/ableplayer)** is a fully
accessible cross-browser media player created by accessibility specialist
Terrill Thompson. It uses the HTML5 `<audio>` or `<video>` element for browsers that support them.

Details on the usage of the Able Player library, including up-to-date support
for filetypes and third-party media hosts, may be found at the [**Able Player
  GitHub page**](https://github.com/ableplayer/ableplayer).


#### Requirements

This module requires no modules outside of Drupal core.

This module requires the Drupal core Media module.


### Installation

  1. Download and enable the module using Composer, Drush, or file upload.
  2. Enable the module either either through the Drupal admin
     (Extend › Media › Ableplayer) or Drush (`drush pm-enable ableplayer`).


### Configuration

  1. Create a Video Media type or edit an existing Video media type from
     Structure › Media › Add (`admin/structure/media/add`) and select Video file as the media source.
  2. Set the video file format to Ableplayer from Structure › Media Types › Video
     › Manage display (`/admin/structure/media/manage/videos/display`) under the display column. Change this display to use Ableplayer.
  3. If you're using remote video, repeat Step 2 for the Remote Video media
     type.


### Usage

  1. Navigate to Content › Media and select the 'Add media' button.
  2. Upload your video file and associated captions, descriptions, chapters, poster image, or sign language video, if applicable.
  3. Attach the video to content using the desired method (Media Browser, entity reference, etc.)
  6. The video will render with the Able Player wrapper and controls. The output
     should be similar to that found in the [**Able Player examples**](https://ableplayer.github.io/ableplayer/demos/video1.html).
