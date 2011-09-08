# ROCKHARBOR Theme Base

This WordPress theme contains all of the necessary components for creating a 
subsite.

## Usage

### Directory structure

At the very least, the following files should be created.

    /<theme_name>
        /css
            colors.css
        /libs
            <template_name>_theme.php
        functions.php
        style.css

### Required files

The `style.css` file should create the following

    /*
    Theme Name: <Template Name>
    Description: Subsite description
    Version: 0.1
    Author: Your name
    Template: rockharbor
    */

The class within `/libs` should extend `RockharborThemeBase`.

Next, create a `functions.php` file

    function _include_theme() {
        require_once 'libs/<template_name>_theme.php';
        global $theme;
        $theme = new <TemplateName>Theme();
    }
    add_action('after_setup_theme', '_include_theme');

This is required because WordPress doesn't use OOP and includes files in the
opposite order one would suspect.

Finally, the color stylesheet `colors.css` needs to be created to customize the
theme colors. Copy the `.less` file from the ROCKHARBOR base theme, customize
the colors and compile it into a `.css` file.

