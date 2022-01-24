<?php
require('qcubed.inc.php');

use QCubed as Q;
use QCubed\Bootstrap as Bs;
use QCubed\Project\Control\ControlBase;
use QCubed\Project\Control\FormBase as Form;

class ExamplesForm extends Form
{
    protected $btnSingle;
    protected $btnMultiple;
    protected $btnFolder;
    protected $btnGroup1;
    protected $btnGroup2;

    protected function formCreate()
    {
        $this->btnSingle = new Q\Plugin\BsFileControl($this);
        $this->btnSingle->Text = t('Single upload');
        $this->btnSingle->Glyph = 'fa fa-upload';
        $this->btnSingle->CssClass = 'btn btn-default  fileinput-button';

        $this->btnMultiple = new Q\Plugin\BsFileControl($this);
        $this->btnMultiple->Text = t('File upload');
        $this->btnMultiple->Glyph = 'fa fa-upload';
        $this->btnMultiple->CssClass = 'btn btn-success fileinput-button';
        $this->btnMultiple->Multiple = true;

        $this->btnFolder = new Q\Plugin\BsFileControl($this);
        $this->btnFolder->Text = t('Folder upload');
        $this->btnFolder->Glyph = 'fa fa-folder-open';
        $this->btnFolder->CssClass = 'btn btn-primary fileinput-button';
        $this->btnFolder->Multiple = true;
        $this->btnFolder->Folder = true;

        $this->btnGroup1 = new Q\Plugin\BsFileControl($this);
        $this->btnGroup1->Text = t('File upload');
        //$this->btnGroup1->Glyph = 'fa fa-upload';
        $this->btnGroup1->CssClass = 'btn btn-orange fileinput-button';
        $this->btnGroup1->Multiple = true;
        $this->btnGroup1->UseWrapper = false;

        $this->btnGroup2 = new Q\Plugin\BsFileControl($this);
        $this->btnGroup2->Text = t('Folder upload');
        //$this->btnGroup2->Glyph = 'fa fa fa-folder-open';
        $this->btnGroup2->CssClass = 'btn btn-orange fileinput-button';
        $this->btnGroup2->Multiple = true;
        $this->btnGroup2->Folder = true;
        $this->btnGroup2->UseWrapper = false;
    }

}
ExamplesForm::Run('ExamplesForm');
