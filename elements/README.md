# Elements

In an attempt to pull display logic outside of db interaction, we've created
these reusable elements that can be displayed anywhere in the site. To use them,
use the methods in the theme base.

    global $theme;
    // pass a variable to the element
    $theme->set('variable', 'value');
    // render the element here
    echo $theme->render('facebook');

Elements should try as hard as possible to be reusable and to not contain any 
database logic themselves.