## Able Player

**Able Player** is a fully accessible cross-browser media player created by accessibility specialist Terrill Thompson. It uses the HTML5 <audio> or <video> element for browsers that support them. The Able Player module integrates the jQuery Able Player plugin as a Drupal JavaScript library for use in other projects.

## Dependencies

### Modules

*   [Libraries API](https://www.drupal.org/project/libraries)
*   [jQuery Update](https://www.drupal.org/project/jquery_update) (>=1.7)
*   [Modernizr](https://www.drupal.org/project/modernizr)

### Libraries

*   [Able Player](https://github.com/ableplayer/ableplayer)
*   jQuery
*   Modernizr

## Installation

1.  Download and install the required modules: Libraries, jQuery Update, and Modernizr.
2.  Download the latest release of Able Player from the GitHub project [releases page](https://github.com/ableplayer/ableplayer/releases).
3.  Extract the archive to the libraries directory (usually **sites/all/libraries**).
4.  Rename the extracted directory to "ableplayer" if it is not already. The final installation path should be **sites/all/libraries/ableplayer**.
5.  Navigate to **admin/config/development/modernizr**. Click the **Download your Modernizr production build** button. This will open the Modernizr build webpage.
6.  Click the **Download** button and save the file to **sites/all/libraries/modernizr**. Rename the file to **custom.modernizr.js**.
7.  Navigate to **admin/config/development/jquery_update**. Ensure that the jQuery version is at least 1.7.
8.  Finally, download and install the Able Player module.

## Usage

Use the following code to invoke Able Player in a module or theme. This will load **ableplayer.min.js**, **jquery.cookie.js**, and Able Player stylesheets.`  

## Known Issues

*   The **jquery.cookie.js** included with Able Player conflicts with the **jquery.cookie.js** loaded by the core Toolbar module, preventing Able Player from working. **Current solution:** Disable the core Toolbar module and use a contrib module such as Admin Toolbar or Navbar.

`
