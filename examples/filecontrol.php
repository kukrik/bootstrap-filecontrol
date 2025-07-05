<?php
require('qcubed.inc.php');

use QCubed as Q;
use QCubed\Bootstrap as Bs;
use QCubed\Plugin as Qp;
use QCubed\Project\Control\FormBase as Form;
use QCubed\Exception\Caller;

class ExamplesForm extends Form
{
    protected Qp\BsFileControl $btnSingle;
    protected Qp\BsFileControl $btnMultiple;
    protected Qp\BsFileControl $btnFolder;
    protected Qp\BsFileControl $btnGroup1;
    protected Qp\BsFileControl $btnGroup2;

    /**
     * Initializes file upload controls for single and multiple files, as well as folder uploads.
     * Configures file controls with different styles, icons, and behaviors such as allowing multiple file uploads or folder uploads.
     *
     * @return void
     * @throws Caller
     */
    protected function formCreate(): void
    {
        $this->btnSingle = new Qp\BsFileControl($this);
        $this->btnSingle->Text = t('Single upload');
        $this->btnSingle->Glyph = 'fa fa-upload';
        $this->btnSingle->CssClass = 'btn btn-default  fileinput-button';

        $this->btnMultiple = new Qp\BsFileControl($this);
        $this->btnMultiple->Text = t('File upload');
        $this->btnMultiple->Glyph = 'fa fa-upload';
        $this->btnMultiple->CssClass = 'btn btn-success fileinput-button';
        $this->btnMultiple->Multiple = true;

        $this->btnFolder = new Qp\BsFileControl($this);
        $this->btnFolder->Text = t('Folder upload');
        $this->btnFolder->Glyph = 'fa fa-folder-open';
        $this->btnFolder->CssClass = 'btn btn-primary fileinput-button';
        $this->btnFolder->Multiple = true;
        $this->btnFolder->Folder = true;

        $this->btnGroup1 = new Qp\BsFileControl($this);
        $this->btnGroup1->Text = t('File upload');
        //$this->btnGroup1->Glyph = 'fa fa-upload';
        $this->btnGroup1->CssClass = 'btn btn-orange fileinput-button';
        $this->btnGroup1->Multiple = true;
        $this->btnGroup1->UseWrapper = false;

        $this->btnGroup2 = new Qp\BsFileControl($this);
        $this->btnGroup2->Text = t('Folder upload');
        //$this->btnGroup2->Glyph = 'fa fa fa-folder-open';
        $this->btnGroup2->CssClass = 'btn btn-orange fileinput-button';
        $this->btnGroup2->Multiple = true;
        $this->btnGroup2->Folder = true;
        $this->btnGroup2->UseWrapper = false;
    }

}
ExamplesForm::run('ExamplesForm');
