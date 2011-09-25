# ROCKHARBOR Theme Base

This WordPress theme contains all of the necessary components for creating a 
subsite. Subsites require a few special files for themselves to customize the
colors.

## Usage

### Directory structure

At the very least, the following files should be created.

    /<theme_name>
        /img
            direction-icon.png
            feature-1-hover.png
            feature-2-hover.png
            header.jpg
            out.png
            twitter-icon.png
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

Also required is a class within `/libs` called `<TemplateSlug>Theme()`. It must
extend `RockharborThemeBase`. All that needs to be defined are the `$themeOptions` 
as explained in the base class.

Next, create a `functions.php` file

    function _include_theme() {
        require_once 'libs/<template_slug>_theme.php';
        global $theme;
        $theme = new <TemplateSlug>Theme();
    }
    add_action('after_setup_theme', '_include_theme');

This is required because WordPress doesn't use OOP and includes files in the
opposite order one would suspect. It is needed to overwrite the global `$theme`.

There are some images required by the subsites, indicated in the directory
structure.

Finally, the stylesheet `style.css` needs to be created to customize the
theme colors. Copy the `style.less` file from the ROCKHARBOR base theme, customize
the colors and compile it into a `.css` file.

## Specialness

_YAWPH_ (yah-ff)
Yet Another WordPress Hack - A hack to make WordPress work in the way you might
imagine it to, or to work in a way common programming practices would dictate.
While much of the site is made up of YAWPHs, I've only begun to document them
as of this commit.