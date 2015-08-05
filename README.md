**[Able Player](https://github.com/ableplayer/ableplayer)** is a fully
accessible cross-browser media player created by accessibility specialist
Terrill Thompson. It uses the HTML5 <audio> or <video> element for browsers
that support them. The Able Player module integrates the jQuery Able Player
plugin as a file formatter with support for captions, transcripts, and audio
description.

## Dependencies

### Modules

*   [Libraries API](https://www.drupal.org/project/libraries)
*   [jQuery Update](https://www.drupal.org/project/jquery_update)
*   [Modernizr](https://www.drupal.org/project/modernizr)
*   [File Entity](https://www.drupal.org/project/file_entity)
*   [Entity Translation](https://www.drupal.org/project/entity_translation)
 (optional, for multilingual transcript support)

### Libraries

*   [Able Player](https://github.com/ableplayer/ableplayer)
*   jQuery
*   Modernizr

## Installation

1.  Download and install the required modules, listed above.
2.  Download the latest release of Able Player from the GitHub project
[releases page](https://github.com/ableplayer/ableplayer/releases).
3.  Extract the archive to the libraries directory
(usually **sites/all/libraries**).
4.  Rename the extracted directory to **ableplayer** if it is not already.
The final installation path should be **sites/all/libraries/ableplayer**.
5.  Download this module to **sites/all/modules** and enable it.
6.  Navigate to **admin/config/development/modernizr**.
Click the **Download your Modernizr production build** button.
This will open the Modernizr build webpage.
7.  Click the **Download** button and save the file
to **sites/all/libraries/modernizr**.
8.  Rename the file to **custom.modernizr.js**.
9.  Navigate to **admin/config/development/jquery_update**.
Ensure that the jQuery version is at least 1.7.

## Configuration

Able Player releases include minified production code as well as the
human-readable source. To use the source code for debugging purposes,
navigate to **Site configuration > Media > Able Player** and select
the **Development** option.

## Usage

### Rendered File Display

The Able Player module provides file displays for supported audio and video
mimetypes. Your site must be configured to handle these mimetypes and set to
use Able Player as the primary display.

1.  Navigate to **File Types > Audio > Manage File Display**.
2.  Check Able Player.
3.  Uncheck any undesired displays.
4.  Below the display selection list, there is a draggable list of enabled
displays. Ensure that Able Player is first in this list.
5.  Click **Save settings**.

Repeat these steps for the video file type. Once these steps are completed,
follow the next set of instructions to display the contents of a file field
with Able Player.

### File Field Display

1.  Add a field of type **File** to any content type. Set the widget type
to **File**.
2.  Navigate to
**Structure > Content Type > YOUR_CONTENT_TYPE > Manage Display**.
This page shows a list of fields attached to a content type as well as their
visibility and format.
3.  Find the row for the File field created in step 1\.
Under the **Format** column, select **Rendered File** from the drop-down.
4.  Click **Save**.

Provided that Able Player has been enabled as the default file display for
the appropriate file types, the file field should now be formatted
with Able Player.

### Video Captions and Audio Descriptions

A caption field instance is attached to the video and audio file types upon
installation of the Able Player module. When a valid WebVTT file is uploaded
to this field, it will be displayed by Able Player automatically.

#### Multilingual Transcripts

The [Entity Translation](https://drupal.org/project/entity_translation) module
is **required** for support of transcripts in multiple languages.

##### Part 1: Enable and configure Entity Translation

1.  Download and install the **Entity Translation** module
2.  Navigate to **admin/config/regional/entity_translation**
3.  Expand the **Translatable entity types** fieldset and check **File**
4.  Click **Save configuration**
5.  A tabbed fieldset should appear at the bottom of the page. Click on **File**
6.  Expand the **Document** fieldset. Uncheck **Hide language selector**.
7.  Click **Save configuration**

##### Part 2: Configure multilingual support on your Drupal site

1.  Enable the core **Locale** module.
2.  Navigate to **admin/config/regional/language.**
3.  Click on **Add language**. Select the language that you want to add from
the select box and click **Save** Repeat for each language that you want
to support.

##### Part 3: Set the language of transcript files

1.  Navigate to an existing transcript file, or first attach a new one to a
video or audio file. Click the **Edit** administration tab.
2.  There should be a **Language** select box available. Choose the
appropriate language for the transcript from this form element.
3.  Click **Save**.

With the language set on each transcript file, Able Player will display a
language selection interface for captions and transcripts. Users will be
able to choose their language at playback time.

### Supported File Types

Details on the usage of the Able Player library, including up-to-date support
for filetypes and third-party media hosts, may be found at
the [**Able Player GitHub page**](https://github.com/ableplayer/ableplayer).

## Known Issues

*   The **jquery.cookie.js** included with Able Player conflicts with
the **jquery.cookie.js** loaded by the core Toolbar module, preventing Able
Player from working. **Current solution:** Disable the core Toolbar module
and use a contrib module such as Admin Toolbar or Navbar.
*   Able player settings cannot be set on a per-field (or per-file) basis at
this time.

## Future Enhancements

*   Allow multiple file sources to be displayed by Able Player for a file
instance, for maximum browser compatibility.
