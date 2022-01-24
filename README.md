# QCubed BsFileControl


## BsFileControl for QCubed v4

QCubed-4's standard FileControl upload class has been redesigned for the Bootstrap wrapper.

This standard upload look is now obsolete https://qcubed.eu/vendor/qcubed-4/application/assets/php/examples/other_controls/file_control.php. Should use a new look in the form of an upload button.

The Bootstrap 3.3.7 class is used here and the Blueimp upload button style classes are borrowed from 
https://blueimp.github.io/jQuery-File-Upload.

You can find different settings in filecontrol.php.

![Image of kukrik](screenshot/bcfilecontrol_screenshot.jpg?raw=true)

If you have not previously installed QCubed Bootstrap and twitter bootstrap, run the following actions on the command line of your main installation directory by Composer:
```
    composer require twbs/bootstrap v3.3.7
```
and

```
    composer require kukrik/bootstrap
    composer require kukrik/bootstrap-filecontrol
```

