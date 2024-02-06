<?php
/**
 * Copyright © Agile Codex Ltd. All rights reserved.
 * @website www.agilecodex.com
 */
namespace Acx\BrandSlider\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

/**
 * Frontend index controller
 */
class Index extends Action implements HttpGetActionInterface
{
    /** @var PageFactory  */
    protected PageFactory $pageFactory;

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory
    ) {
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        return $this->pageFactory->create();
    }

}
