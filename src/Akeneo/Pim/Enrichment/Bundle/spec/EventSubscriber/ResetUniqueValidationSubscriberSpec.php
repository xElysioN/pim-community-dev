<?php

namespace spec\Akeneo\Pim\Enrichment\Bundle\EventSubscriber;

use PhpSpec\ObjectBehavior;
use Akeneo\Pim\Enrichment\Component\Product\Validator\UniqueAxesCombinationSet;
use Akeneo\Pim\Enrichment\Component\Product\Validator\UniqueValuesSet;

class ResetUniqueValidationSubscriberSpec extends ObjectBehavior
{
    function let(UniqueValuesSet $uniqueValueSet, UniqueAxesCombinationSet $uniqueAxesCombinationSet)
    {
        $this->beConstructedWith($uniqueValueSet, $uniqueAxesCombinationSet);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Akeneo\Pim\Enrichment\Bundle\EventSubscriber\ResetUniqueValidationSubscriber');
    }

    function it_should_reset_unique_value_set($uniqueValueSet, $uniqueAxesCombinationSet)
    {
        $uniqueValueSet->reset()->shouldBeCalled();
        $uniqueAxesCombinationSet->reset()->shouldBeCalled();

        $this->onAkeneoBatchItemStepAfterBatch();
    }
}
