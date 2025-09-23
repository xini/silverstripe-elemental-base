<?php

namespace Fromholdio\Elemental\Base\Extensions;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Core\Extension;

class ElementalAreaControllerExtension extends Extension
{
    public function updateApiReadElementalArea(&$data, $request): void
    {
        foreach ($data as $i => $elementData) {
            $id = $elementData['id'];
            $element = BaseElement::get()->find('ID', $id);
            if ($element?->exists()) {
                $data[$i]['title'] = $element->getInlineCMSTitle();
            }
        }
    }
}
