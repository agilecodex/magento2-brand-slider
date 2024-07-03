<?php
/**
 *  Copyright © Agile Codex Ltd. All rights reserved.
 *  License: https://www.agilecodex.com/license-agreement
 */
namespace Acx\BrandSlider\Controller\Adminhtml\Brand;

use Acx\BrandSlider\Controller\Adminhtml\Brand as AbastractBrand;
use Acx\BrandSlider\Model\Brand;
use Acx\BrandSlider\Service\ImageService;
use Acx\BrandSlider\Model\BrandFactory;
use Acx\BrandSlider\Model\BrandRepository;
use Acx\BrandSlider\Model\ResourceModel\Brand\CollectionFactory;
use Magento\Backend\App\Action\Context as BackendContext;
use Magento\Backend\Helper\Js;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Action class for saving brand.
 *
 * @author Agile Codex
 */
class Save extends AbastractBrand
{
    /** @var ImageService  */
    protected $imageService;

    /** @var BrandRepository */
    protected $brandRepository;

    /** @var EventManager */
    private $eventManager;

    /**
     * @param BackendContext $context
     * @param UploaderFactory $uploaderFactory
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
        ImageService $imageService,
        BrandFactory $brandFactory,
        CollectionFactory $brandCollectionFactory,
        Registry $coreRegistry,
        FileFactory $fileFactory,
        PageFactory $resultPageFactory,
        LayoutFactory $resultLayoutFactory,
        ForwardFactory $resultForwardFactory,
        StoreManagerInterface $storeManager,
        Js $jsHelper,
        EventManager $eventManager,
        BrandRepository $brandRepository
    ) {
        parent::__construct($context, $brandFactory, $brandCollectionFactory,
                $coreRegistry, $fileFactory, $resultPageFactory, $resultLayoutFactory,
                $resultForwardFactory, $storeManager, $jsHelper);
        $this->imageService = $imageService;
        $this->brandRepository = $brandRepository;
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

            $data = $this->imageService->beforeSave($data, 'logo');
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
    }/**
 * Do not save empty image value to DB if image was not uploaded.
 *
 * @param \Magento\Framework\DataObject $object
 * @return \Magento\Framework\DataObject $object
 */
    public function beforeSave($object)
    {
        $attributeName = 'logo';

        $value = $object[$attributeName];

        if ($this->isTmpFileAvailable($value) && $imageName = $this->getUploadedImageName($value)) {
            try {
                /** @var StoreInterface $store */
                $store = $this->storeManager->getStore();
                $baseMediaDir = $store->getBaseMediaDir();
                $newImgRelativePath = $this->imageUploader->moveFileFromTmp($imageName, true);
                $value[0]['url'] = '/' . $baseMediaDir . '/' . $newImgRelativePath;
                $value[0]['name'] = $value[0]['url'];
            } catch (\Exception $e) {
                $this->_logger->critical($e);
            }
        } elseif ($this->fileResidesOutsideCategoryDir($value)) {
            //todo
            $uri = \Laminas\Uri\UriFactory::factory($value[0]['url']);
            $query = $uri->getPath();
            $value[0]['url'] = parse_url($value[0]['url'], PHP_URL_PATH);
            $value[0]['name'] = $value[0]['url'];
        }

        if ($imageName = $this->getUploadedImageName($value)) {
            if (!$this->fileResidesOutsideCategoryDir($value)) {
                $imageName = $this->checkUniqueImageName($imageName);
            }
            $object[$attributeName] = $imageName;
        } elseif (!is_string($value)) {
            $object[$attributeName] = null;
        }
        return $object;
    }

}
