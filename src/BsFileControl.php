<?php
/**
 *
 * Adapted this part to work with the QCubed-4 PHP framework and the Bootstrap 3.3.7 wrapper.
 *
 * @license MIT
 *
 */

namespace QCubed\Plugin;

use QCubed\Bootstrap as Bs;
use QCubed\Exception\InvalidCast;
use QCubed\Project\Control\ControlBase;
use QCubed\Exception\Caller;
use QCubed\Type;
use QCubed as Q;

/**
 * Class BsFileControl
 *
 * This class will render an HTML File input.
 *
 * @property string $Glyph is optional, whether to add an icon inside the button
 * @property boolean $Multiple is optional, whether to allow multiple files or a single file to upload
 * @property boolean $Folder is optional, whether to allow the folder to be uploaded
 * @property-read string $FileName is the name of the file that the user uploads
 * @property-read string $Type is the MIME type of the file
 * @property-read integer $Size is the size in bytes of the file
 * @property-read string $File is the temporary full file path on the server where the file physically resides
 *
 * @package QCubed\Plugin
 */
class BsFileControl extends Q\Control\BlockControl
{
    protected $strTagName = "input";

    protected $strGlyph;
    protected $blnMultiple;
    protected $blnFolder;

    protected $strFileName = null;
    protected $strType = null;
    protected $intSize = null;
    protected $strFile = null;

    // SETTINGS
    protected $strFormAttributes = array('enctype' => 'multipart/form-data');

    public function __construct($objParentObject, $strControlId = null)
    {
        try {
            parent::__construct($objParentObject, $strControlId);
        } catch (Caller  $objExc) {
            $objExc->incrementOffset();
            throw $objExc;
        }
        $this->registerFiles();
    }

    protected function registerFiles()
    {
        $this->AddCssFile(QCUBED_BOOTSTRAP_CSS); // make sure they know
        $this->AddCssFile(QCUBED_FONT_AWESOME_CSS); // make sure they know
        $this->addCssFile(dirname(QCUBED_BASE_URL) . "/kukrik/bootstrap-filecontrol/assets/css/jquery.fileupload.css");
        $this->addCssFile(dirname(QCUBED_BASE_URL) . "/kukrik/bootstrap-filecontrol/assets/css/jquery.fileupload-ui.css");
        $this->addCssFile(dirname(QCUBED_BASE_URL) . "/kukrik/bootstrap-filecontrol/assets/css/example.css");

        //$this->addCssFile(dirname(QCUBED_BASE_URL) . "/assets/css/jquery.fileupload-noscript.css");
        //$this->addCssFile(dirname(QCUBED_BASE_URL) . "/assets/css/jquery.fileupload-noscript-ui.css");
    }

    /**
     * @return void
     * @throws Caller
     * @throws Q\Exception\InvalidCast
     */
    public function parsePostData()
    {
        // Check to see if this Control's Value was passed in via the POST data
        if ((array_key_exists($this->strControlId, $_FILES)) && ($_FILES[$this->strControlId]['tmp_name'])) {
            // It was -- update this Control's value with the new value passed in via the POST arguments
            $this->strFileName = $_FILES[$this->strControlId]['name'];
            $this->strType = $_FILES[$this->strControlId]['type'];
            $this->intSize = Type::cast($_FILES[$this->strControlId]['size'], Type::INTEGER);
            $this->strFile = $_FILES[$this->strControlId]['tmp_name'];
        }
    }

    /**
     * Returns the HTML of the control which can be sent to user's browser
     *
     * @return string HTML of the control
     */
    protected function getControlHtml()
    {
        // Reset Internal Values
        $this->strFileName = null;
        $this->strType = null;
        $this->intSize = null;
        $this->strFile = null;

        $attributes = [];
        $attributes['type'] = 'file';
        $attributes['name'] = 'files[]';
        $attributes['id'] = $this->strControlId;

        $strToReturn = $this->renderInput($this->strTagName, $attributes, $this->blnMultiple, $this->blnFolder);
        $strToReturn .= Q\Html::renderTag('span', null, $this->getInnerHtml());
        $strToReturn = $this->renderTag('span', ['class' =>$this->strCssClass], null, $strToReturn);

        return $strToReturn;
    }

    /**
     * Tells if the file control is valid
     *
     * @return bool
     */
    public function validate()
    {
        if ($this->blnRequired) {
            if (strlen($this->strFileName) > 0) {
                return true;
            } else {
                $this->ValidationError = t($this->strName) . ' ' . t('is required');
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * @param $strTag
     * @param $mixAttributes
     * @param $blnMultiple
     * @param $blnFolder
     * @return string|void
     */
    public function renderInput($strTag, $mixAttributes, $blnMultiple = false, $blnFolder = false)
    {
        assert(!empty($strTag));
        $strToReturn = '<' . $strTag;
        if ($mixAttributes) {
            if (is_string($mixAttributes)) {
                $strToReturn .= ' ' . trim($mixAttributes);
            } else {
                // assume array
                $strToReturn .= self::renderHtmlAttributes($mixAttributes);
            }
            if ($blnMultiple) {
                $strToReturn .= ' ' . 'multiple';
            } else {
                $strToReturn .= '';
            }
            if ($blnMultiple && $blnFolder) {
                $strToReturn .= ' ' . 'directory webkitdirectory allowdirs';
            } else {
                $strToReturn .= '';
            }
            $strToReturn .= ' />';
            return $strToReturn;
        }
    }

    /**
     * @return string
     */
    protected function getInnerHtml()
    {
        $strToReturn = parent::getInnerHtml();
        if ($this->strGlyph) {
            $strToReturn = sprintf('<i class="%s" aria-hidden="true"></i> ', $this->strGlyph) . $strToReturn;
        }
        return $strToReturn;
    }

    /**
     * @param $strName
     * @return bool|int|mixed
     * @throws Caller
     */
    public function __get($strName)
    {
        switch ($strName) {
            case "Glyph": return $this->strGlyph;
            case "Multiple": return $this->blnMultiple;
            case "Folder": return $this->blnFolder;
            case "CssClass": return $this->strCssClass;
            case "FileName": return $this->strFileName;
            case "Type": return $this->strType;
            case "Size": return $this->intSize;
            case "File": return $this->strFile;

            default:
                try {
                    return parent::__get($strName);
                } catch (Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
        }
    }

    /**
     * @param $strName
     * @param $mixValue
     * @return void
     * @throws Caller
     * @throws InvalidCast
     */
    public function __set($strName, $mixValue)
    {
        switch ($strName) {
            case "Glyph":
                $this->strGlyph = Type::cast($mixValue, Type::STRING);
                break;
            case "Multiple":
                $this->blnMultiple = Type::cast($mixValue, Type::BOOLEAN);
                break;
            case "Folder":
                $this->blnFolder = Type::cast($mixValue, Type::BOOLEAN);
                break;
            case "CssClass":
                $this->strCssClass = Type::cast($mixValue, Type::STRING);
                break;

            default:
                try {
                    parent::__set($strName, $mixValue);
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
                break;
        }
    }
}