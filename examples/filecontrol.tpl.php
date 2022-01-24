<?php require(QCUBED_CONFIG_DIR . '/header.inc.php'); ?>
    <style >
        body {font-size: medium;}
        p, footer {font-size: medium;}
    </style>

<?php $this->RenderBegin(); ?>

    <div class="instructions">
        <h1 class="instruction_title" style="padding-bottom: 15px;">QCubed-4's standard FileControl upload class has been
            redesigned for the Bootstrap wrapper</h1>
        <p>This standard upload look is now obsolete
            <a href="https://qcubed.eu/vendor/qcubed-4/application/assets/php/examples/other_controls/file_control.php" target="_blank">https://qcubed.eu/vendor/qcubed-4/application/assets/php/examples/other_controls/file_control.php</a>. Should use a new look in the form of an upload button.
            The Bootstrap 3.3.7 class is used here and the Blueimp upload button style classes are borrowed from
            <a href="https://blueimp.github.io/jQuery-File-Upload" target="_blank">https://blueimp.github.io/jQuery-File-Upload</a>.</p>
        <p>You can find different settings in filecontrol.php.</p>
    </div>

    <div class="form-horizontal" style="padding-top: 25px; padding-bottom: 25px;">
        <div class="row">
            <div class="col-sm-2">
                <?= _r($this->btnSingle); ?>
            </div>
            <div class="col-sm-2">
                <?= _r($this->btnMultiple); ?>
            </div>
            <div class="col-sm-2">
                <?= _r($this->btnFolder); ?>
            </div>
            <div class="col-sm-4">
                <div class="btn-group" role="group">
                    <?= _r($this->btnGroup1); ?>
                    <?= _r($this->btnGroup2); ?>
                </div>
            </div>
        </div>
    </div>

<?php $this->RenderEnd(); ?>

<?php require(QCUBED_CONFIG_DIR . '/footer.inc.php'); ?>
