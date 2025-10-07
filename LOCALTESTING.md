# Local Testing
------------

Setup your local environment with [DDEV]. This project leverages the
[DDEV Drupal Contrib] plugin.

1.  [Install DDEV] with a [Docker provider].
2.  Clone this project's repository

        git clone git@github.com:ubc-web-services/ubc_cwl_auth.git
        cd ubc_cwl_auth

3.  Startup DDEV.

        ddev start

4.  Install composer dependencies.

        ddev poser

    Note: `ddev poser` is shorthand for `ddev composer` to add in Drupal core dependencies
    without needing to modify the root composer.json. Find out more in DDEV Drupal Contrib
    [commands].

4.  Symlink the theme into the `web/themes/custom` directory.

        ddev symlink-project


6.  Install Drupal and set the theme.

        ddev install

7.  Visit site in browser.

        ddev describe

    Or, login as user 1:

        ddev drush uli