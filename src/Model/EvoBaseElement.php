<?php

namespace Fromholdio\Elemental\Base\Model;

use DNADesign\Elemental\Models\BaseElement;
use Fromholdio\Elemental\Base\EvoElementTrait;
use SilverStripe\Forms\FieldList;

class EvoBaseElement extends BaseElement
{
    use EvoElementTrait;

    private static $table_name = 'EvoBaseElement';
    private static $singular_name = 'Element';
    private static $plural_name = 'Elements';
    private static $description = 'Base element';


    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            return $this->overrideCoreCMSFields($fields);
        });
        $this->afterUpdateCMSFields(function (FieldList $fields) {
            $fields = $this->insertContentTabSet($fields);
            $fields = $this->insertSettingsTabSet($fields);
        });
        return parent::getCMSFields();
    }
}
