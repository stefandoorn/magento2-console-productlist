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
class ProducttypesCommand extends Command
{

    /**
     * @var Productlist
     */
    private $productlist;

    /**
     * ProducttypesCommand constructor.
     * @param Productlist $productlist
     */
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
        $this->setName('products:types')
            ->setDescription('Get list of product types');

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Get data
        $rows = new \ArrayObject();
        foreach($this->productlist->getProductTypes() as $id => $type) {
            $rows->append([$type->getName(), $type->getLabel()]);
        }

        // Output table layout
        $table = new Table($output);
        $table->setHeaders(['Name', 'Label']);
        $table->setRows($rows->getArrayCopy());
        $table->render();
    }

}
