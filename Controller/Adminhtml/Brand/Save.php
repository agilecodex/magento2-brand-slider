<?php
/**
 *  Copyright © Agile Codex Ltd. All rights reserved.
 *  License: https://www.agilecodex.com/license-agreement
 */
namespace Acx\BrandSlider\Controller\Adminhtml\Brand;

use Acx\BrandSlider\Controller\Adminhtml\Brand as AbastractBrand;
use Acx\BrandSlider\Model\Brand;
use Acx\BrandSlider\Model\Brand\Image;
use Acx\BrandSlider\Model\BrandFactory;
use Acx\BrandSlider\Model\BrandRepository;
use Acx\BrandSlider\Model\ResourceModel\Brand\CollectionFactory;
use Magento\Backend\App\Action\Context as BackendContext;
use Magento\Backend\Helper\Js;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Action class for saving brand.
 *
 * @author Agile Codex
 */
class Save extends AbastractBrand
{
    /** @var UploaderFactory  */
    protected $uploaderFactory;

    /** @var Image  */
    protected $imageModel;

    /** @var BrandRepository */
    protected $brandRepository;

    /** @var DataPersistorInterface */
    protected $dataPersistor;

    /** @var EventManager */
    private $eventManager;

    /**
     * @param BackendContext $context
     * @param UploaderFactory $uploaderFactory
     * @param Image $imageModel
     * @param BrandFactory $brandFactory
     * @param CollectionFactory $brandCollectionFactory
     * @param Registry $coreRegistry
     * @param FileFactory $fileFactory
     * @param PageFactory $resultPageFactory
     * @param LayoutFactory $resultLayoutFactory
     * @param ForwardFactory $resultForwardFactory
     * @param StoreManagerInterface $storeManager
     * @param Js $jsHelper
     * @param DataPersistorInterface $dataPersistor
     * @param EventManager $eventManager
     * @param BrandRepository $brandRepository
     */
    public function __construct(
        BackendContext $context,
        UploaderFactory $uploaderFactory,
        Image $imageModel,
        BrandFactory $brandFactory,
        CollectionFactory $brandCollectionFactory,
        Registry $coreRegistry,
        FileFactory $fileFactory,
        PageFactory $resultPageFactory,
        LayoutFactory $resultLayoutFactory,
        ForwardFactory $resultForwardFactory,
        StoreManagerInterface $storeManager,
        Js $jsHelper,
        DataPersistorInterface $dataPersistor,
        EventManager $eventManager,
        BrandRepository $brandRepository
    ) {
        parent::__construct($context, $brandFactory, $brandCollectionFactory,
                $coreRegistry, $fileFactory, $resultPageFactory, $resultLayoutFactory,
                $resultForwardFactory, $storeManager, $jsHelper);

        $this->uploaderFactory = $uploaderFactory;
        $this->imageModel = $imageModel;
        $this->brandRepository = $brandRepository;
        $this->dataPersistor = $dataPersistor;
        $this->eventManager = $eventManager;
    }

    /**
     * @inheritDoc
     */
    public function execute() {
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data = $this->getRequest()->getPostValue()) {
            if (isset($data['status']) && $data['status'] === 'true') {
                $data['status'] = Brand::STATUS_ENABLED;
            }
            if (empty($data['brand_id'])) {
                $data['brand_id'] = null;
            }
            $model = $this->_brandFactory->create();

            if ($id = $this->getRequest()->getParam(static::PARAM_CRUD_ID)) {
                try {
                    $model = $this->brandRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This brand no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $this->dataPersistor->set('brandslider_brand', $data);
            $data = $this->imageModel->beforeSave($data);
            $oldData = $model->getData();
            $model->setData($data);

            try {
                $brand = $this->brandRepository->save($model);

                $this->messageManager->addSuccess(__('The brand has been saved.'));
                $this->eventManager->dispatch('acx_brand_slider_brand_save_after',
                    ['entity' => $brand, 'oldData' => $oldData]);

                $this->_getSession()->setFormData(false);

                return $this->_getBackResultRedirect($resultRedirect, $model->getId());
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->messageManager->addException($e, __('Something went wrong while saving the brand.'));
            }

            $this->_getSession()->setFormData($data);

            return $resultRedirect->setPath(
                '*/*/edit',
                [static::PARAM_CRUD_ID => $this->getRequest()->getParam(static::PARAM_CRUD_ID)]
            );
        }

        return $resultRedirect->setPath('*/*/');
    }

}
