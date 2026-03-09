<?php
declare(strict_types=1);

namespace Tbo\TboThemeBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(type: 'tbo_teaser_box', category: 'tbo_elements', template: 'content_element/tbo_teaser_box')]
class TeaserBoxController extends AbstractContentElementController
{
    protected function getResponse(Template $template, ContentModel $model, Request $request): Response
    {
        return $template->getResponse();
    }
}
