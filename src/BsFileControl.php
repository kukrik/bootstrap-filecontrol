<?php
/**
 *
 * Adapted this part to work with the QCubed-4 PHP framework and the Bootstrap 3.3.7 wrapper.
 *
 * @license MIT
 *
 */

namespace QCubed\Plugin;

use QCubed\Exception\InvalidCast;
use QCubed\Project\Control\ControlBase;
use QCubed\Project\Control\FormBase;
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
    protected string $strTagName = "input";

    protected ?string $strGlyph = null;
    protected ?bool $blnMultiple = null;
    protected ?bool $blnFolder = null;
    protected string $strCssClass = "btn btn-default fileinput-button";

    protected ?string $strFileName = null;
    protected ?string $strType = null;
    protected ?int $intSize = null;
    protected ?string $strFile = null;

    // SETTINGS
    protected array $strFormAttributes = array('enctype' => 'multipart/form-data');

    /**
     * Initializes a new instance of the class, setting the parent object and optional control ID.
     * Registers required files during construction.
     *
     * @param ControlBase|FormBase $objParentObject The parent object, either a control or form.
     * @param string|null $strControlId An optional control ID for the instance.
     *
     * @throws Caller
     */
    public function __construct(ControlBase|FormBase $objParentObject, ?string $strControlId = null)
    {
        try {
            parent::__construct($objParentObject, $strControlId);
        } catch (Caller  $objExc) {
            $objExc->incrementOffset();
            throw $objExc;
        }
        $this->registerFiles();
    }

    /**
     * Registers and includes the required CSS files for the component.
     *
     * @return void
     * @throws Caller
     */
    protected function registerFiles(): void
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
     * Parses the POST data to update the control's value based on the uploaded file details.
     *
     * @return void
     * @throws Caller
     * @throws InvalidCast
     */
    public function parsePostData(): void
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
     * Returns the HTML of the control which can be sent to the user's browser
     *
     * @return string HTML of the control
     */
    protected function getControlHtml(): string
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
        return $this->renderTag('span', ['class' =>$this->strCssClass], null, $strToReturn);
    }

    /**
     * Tells if the file control is valid
     *
     * @return bool
     */
    public function validate(): bool
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
     * Renders an HTML input tag with provided attributes and optional properties for multiple and folder input handling.
     *
     * @param string $strTag The HTML tag to render.
     * @param array|string $mixAttributes The attributes to add to the HTML tag. Can be a string or an associative array.
     * @param bool $blnMultiple Optional. Indicates if the input allows multiple selections. Defaults to false.
     * @param bool $blnFolder Optional. Indicates if the input involves folder selection. Defaults to false.
     *
     * @return string The rendered HTML string of the input tag.
     */
    public function renderInput(string $strTag, array|string $mixAttributes, ?bool $blnMultiple = false, ?bool $blnFolder = false): string
    {
        assert(!empty($strTag));
        $strToReturn = '<' . $strTag;
        if ($mixAttributes) {
            if (is_string($mixAttributes)) {
                $strToReturn .= ' ' . trim($mixAttributes);
            } else {
                // assume an array
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
        return $strToReturn;
    }


    /**
     * Retrieves the inner HTML content of the element, including an icon represented
     * by a glyph if specified.
     *
     * @return string The inner HTML content, optionally prefixed with a glyph icon.
     */
    protected function getInnerHtml(): string
    {
        $strToReturn = parent::getInnerHtml();
        if ($this->strGlyph) {
            $strToReturn = sprintf('<i class="%s" aria-hidden="true"></i> ', $this->strGlyph) . $strToReturn;
        }
        return $strToReturn;
    }

    /**
     * Magic method to retrieve the value of a property.
     *
     * This method allows access to internal properties by their names.
     * If the requested property is not defined, it delegates to the parent
     * implementation or throws an exception.
     *
     * @param string $strName The name of the property to retrieve.
     *
     * @return mixed The value of the requested property.
     * @throws Caller If the property is not defined or accessible.
     */
    public function __get(string $strName): mixed
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
     * Dynamically sets the value of a property based on the given name.
     * Specific property names are handled internally, casting the value
     * to the appropriate type. If the property name is unrecognized,
     * attempts to call the parent class's __set method.
     *
     * @param string $strName The name of the property to set.
     * @param mixed $mixValue The value to assign to the property.
     *
     * @return void
     * @throws Caller
     * @throws InvalidCast If the property name is not recognized and the parent __set method throws the exception.
     */
    public function __set(string $strName, mixed $mixValue): void
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