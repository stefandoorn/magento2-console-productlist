<?php namespace StefanDoorn\ConsoleProductList\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\ProductTypeListInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Template;
use Magento\Framework\App\State;

class Productlist
{

    /**
     * @var ProductTypeListInterface
     */
    private $productTypeList;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var Status
     */
    private $productStatus;
    /**
     * @var string
     */
    private $type;
    /**
     * @var boolean
     */
    private $status;

    /**
     * Productlist constructor.
     * @param ProductTypeListInterface $productTypeList
     * @param ProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Status $productStatus
     * @param \Magento\Framework\App\State $appState
     */
    public function __construct(ProductTypeListInterface $productTypeList,
                                ProductRepositoryInterface $productRepository,
                                SearchCriteriaBuilder $searchCriteriaBuilder,
                                Status $productStatus,
                                State $appState
    )
    {

        $this->productTypeList = $productTypeList;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->productStatus = $productStatus;
        // Fixes error that area code should be set when using active filter
        $appState->setAreaCode('console');
    }

    /**
     * @return \Magento\Catalog\Api\Data\ProductSearchResultsInterface
     */
    public function getProducts()
    {
        $this->addFilterType();
        $this->addFilterStatus();

        return $this->productRepository->getList($this->searchCriteriaBuilder->create());
    }

    /**
     * @return \Magento\Catalog\Api\Data\ProductTypeInterface[]
     */
    public function getProductTypes()
    {
        return $this->productTypeList->getProductTypes();
    }

    /**
     * @return array
     */
    public function getProductTypesAssoc()
    {
        $rows = [];
        foreach ($this->getProductTypes() as $id => $type) {
            $rows[$type->getName()] = $type->getLabel();
        }
        return $rows;
    }

    /**
     * @param $type
     */
    public function setType($type)
    {
        if (!array_key_exists($type, $this->getProductTypesAssoc())) {
            throw new \InvalidArgumentException(sprintf('Supplied type is not valid: %s', $type));
        }

        $this->type = $type;
    }

    /**
     * @return string
     */
    private function getType()
    {
        return $this->type;
    }

    /**
     * @param $status bool
     */
    public function setStatus($status)
    {
        if (!is_int($status)) {
            throw new \InvalidArgumentException(sprintf('Invalid status: %s', (string)$status));
        }

        $this->status = $status;
    }

    /**
     * @return bool
     */
    private function getStatus()
    {
        return $this->status;
    }

    /**
     * @return \Magento\Framework\Api\Filter
     */
    private function addFilterType()
    {
        if ($this->getType()) {
            $this->searchCriteriaBuilder->addFilter(ProductInterface::TYPE_ID, $this->getType());;
        }
    }

    /**
     * @return \Magento\Framework\Api\Filter
     */
    private function addFilterStatus()
    {
        if ($this->getStatus() === 1) {
            $this->searchCriteriaBuilder->addFilter(ProductInterface::STATUS, $this->productStatus->getVisibleStatusIds(), 'in');
        }
    }

}