<?php

namespace spec\Pim\Bundle\FilterBundle\Filter\Product;

use PhpSpec\ObjectBehavior;
use Akeneo\Pim\Structure\Bundle\Doctrine\ORM\Repository\AssociationTypeRepository;
use Pim\Bundle\DataGridBundle\Datagrid\Request\RequestParametersExtractorInterface;
use Pim\Bundle\FilterBundle\Datasource\FilterDatasourceAdapterInterface;
use Pim\Bundle\FilterBundle\Filter\ProductFilterUtility;
use Akeneo\Pim\Enrichment\Component\Product\Model\AbstractAssociation;
use Akeneo\Pim\Structure\Component\Model\AssociationTypeInterface;
use Akeneo\Pim\Enrichment\Component\Product\Model\ProductInterface;
use Akeneo\Pim\Enrichment\Component\Product\Repository\ProductRepositoryInterface;
use Prophecy\Argument;
use Symfony\Component\Form\FormFactoryInterface;

class IsAssociatedFilterSpec extends ObjectBehavior
{
    function let(
        FormFactoryInterface $factory,
        ProductFilterUtility $utility,
        RequestParametersExtractorInterface $extractor,
        CustomAssociationTypeRepository $assocRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->beConstructedWith($factory, $utility, $extractor, $assocRepository, $productRepository);
    }

    function it_is_an_oro_choice_filter()
    {
        $this->shouldBeAnInstanceOf('Oro\Bundle\FilterBundle\Filter\BooleanFilter');
    }

    function it_applies_a_filter_on_product_when_its_in_an_expected_association(
        FilterDatasourceAdapterInterface $datasource,
        $utility,
        $extractor,
        $assocRepository,
        AssociationTypeInterface $assocType,
        AbstractAssociation $association,
        ProductInterface $productOwner,
        ProductInterface $productAssociatedOne,
        ProductInterface $productAssociatedTwo,
        $productRepository
    ) {
        $extractor->getDatagridParameter('_parameters', [])->willReturn([]);
        $extractor->getDatagridParameter('associationType')->willReturn(1);
        $assocRepository->findOneBy(Argument::any())->willReturn($assocType);

        $extractor->getDatagridParameter('product')->willReturn(11);
        $productRepository->find(11)->willReturn($productOwner);

        $productOwner->getAssociationForType($assocType)->willReturn($association);
        $association->getProducts()->willReturn([$productAssociatedOne, $productAssociatedTwo]);
        $productAssociatedOne->getId()->willReturn(12);
        $productAssociatedTwo->getId()->willReturn(13);

        $utility->applyFilter($datasource, 'id', 'IN', ['12', '13'])->shouldBeCalled();

        $this->apply($datasource, ['type' => null, 'value' => 1]);
    }
}

class CustomAssociationTypeRepository extends AssociationTypeRepository
{
    function findOneByCode()
    {
        return null;
    }
}
