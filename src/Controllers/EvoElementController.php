<?php

namespace Fromholdio\Elemental\Base\Controllers;

use DNADesign\Elemental\Controllers\ElementController;
use DNADesign\Elemental\Models\BaseElement;
use Fromholdio\Elemental\Base\Extensions\ElementsRouter;
use SilverStripe\Control\Controller;
use SilverStripe\Model\ModelDataCustomised;
use SilverStripe\View\SSViewer;

class EvoElementController extends ElementController
{
    private static $extensions = [
        ElementsRouter::class,
    ];

    /**
     * Templates/Rendering
     * ----------------------------------------------------
     */

    public function ElementForTemplate(): ModelDataCustomised
    {
        $custom = $this->getCustomDataForTemplate();
        $element = $this->getElement();
        return $element->customise($custom);
    }

    public function getCustomDataForTemplate(): array
    {
        $data = [];
        $this->extend('updateCustomDataForTemplate', $data);
        return $data;
    }

    public function forTemplate(): string
    {
        // If a redirect has been set (e.g., in init() or other controller methods),
        // we need to actually perform the redirect instead of rendering the element.
        // Since element controllers are rendered within page templates, we can't return
        // an HTTPResponse directly. Instead, we output the redirect response and exit.
        if ($this->getResponse()->isFinished()) {
            $this->getResponse()->output();
            exit;
        }

        $templates = $this->getElement()->getHolderTemplates();
        return empty($templates)
            ? ''
            : $this->renderWith(SSViewer::create($templates));
    }


    /**
     * Links
     * ----------------------------------------------------
     */

    public function HandlerLink(?string $action = null): ?string
    {
        $link = null;
        $element = $this->getElement();
        $segment = $element->getHandlerURLSegment();
        if (!is_null($segment)) {
            $curr = $element->getPage();
            if (!is_null($curr) && $curr->hasMethod('Link')) {
                $link = Controller::join_links(
                    $curr->Link($segment),
                    $action
                );
            }
        }
        return $link;
    }

    public function Link($action = null): ?string
    {
        $link = null;
        $topContainer = $this->getElement()->getTopContainer();
        if ($topContainer?->hasMethod('Link')) {
            $link = Controller::join_links(
                $topContainer->Link($action),
                '#'. $this->getElement()->getAnchor()
            );
        }
        $this->extend('updateLink', $link, $action);
        return $link;
    }

    public function AbsoluteLink($action = null): ?string
    {
        $link = null;
        $topContainer = $this->getElement()->getTopContainer();
        if ($topContainer?->hasMethod('AbsoluteLink')) {
            $link = Controller::join_links(
                $topContainer->AbsoluteLink($action),
                '#'. $this->getElement()->getAnchor()
            );
        }
        $this->extend('updateAbsoluteLink', $link, $action);
        return $link;
    }


    /**
     * Variables/helpers intended/available for use on front-end
     * ----------------------------------------------------
     */

    public function First(): bool
    {
        return $this->getElement()->getExtraData()['First'] ?? false;
    }

    public function Last(): bool
    {
        return $this->getElement()->getExtraData()['Last'] ?? false;
    }

    public function TotalItems(): int
    {
        return $this->getElement()->getExtraData()['TotalItems'] ?? 0;
    }

    public function Pos(): int
    {
        return $this->getElement()->getExtraData()['Pos'] ?? 0;
    }

    public function EvenOdd(): ?string
    {
        return $this->getElement()->getExtraData()['EvenOdd'] ?? null;
    }
}
