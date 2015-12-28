<?php

namespace StefanDoorn\ConsoleProductList\Console\Command;

use StefanDoorn\ConsoleProductList\Model\Productlist;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ProductlistCommand
 */
class ProductlistCommand extends Command
{

    /**
     *
     */
    const TYPE_ARGUMENT = 'type';

    const COUNT_OPTION = 'count';

    const ACTIVE_OPTION = 'active';

    /**
     * @var Productlist
     */
    private $productlist;

    public function __construct(Productlist $productlist)
    {
        parent::__construct();
        $this->productlist = $productlist;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('products:list')
            ->setDescription('Get list of products')
            ->addArgument(
                self::TYPE_ARGUMENT,
                InputArgument::REQUIRED,
                'Type of products (name from products:types)')
            ->addOption(
                self::COUNT_OPTION,
                null,
                InputOption::VALUE_NONE,
                'If set, only count will be returned'
            )
            ->addOption(
                self::ACTIVE_OPTION,
                null,
                InputOption::VALUE_NONE,
                'If set, only active product will be returned'
            );

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Set filters & get data
        $type = $input->getArgument(self::TYPE_ARGUMENT);
        if ($type) {
            $this->productlist->setType($type);
        }
        $active = $input->getOption(self::ACTIVE_OPTION);
        if ($active) {
            $this->productlist->setStatus(1);
        }
        $products = $this->productlist->getProducts();

        // If only count, return it
        if ($input->getOption(self::COUNT_OPTION)) {
            return $output->writeln(sprintf('Count: %d', $products->getTotalCount()));
        }

        // Else prepare data for showing
        $types = $this->productlist->getProductTypesAssoc();
        $rows = new \ArrayObject();
        foreach($products->getItems() as $id => $product) {
            $rows->append([$product->getId(), $product->getSku(), $product->getName(), $types[$product->getTypeId()]]);
        }

        // Output table layout
        $table = new Table($output);
        $table->setHeaders(['ID', 'SKU', 'Name', 'Type']);
        $table->setRows($rows->getArrayCopy());
        $table->render();
    }

}
