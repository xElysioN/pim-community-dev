<?php

namespace spec\Akeneo\Tool\Bundle\VersioningBundle\Normalizer\Flat;

use PhpSpec\ObjectBehavior;
use Akeneo\Tool\Bundle\VersioningBundle\Normalizer\Flat\TranslationNormalizer;
use Akeneo\Pim\Enrichment\Component\Product\Model\GroupInterface;
use Akeneo\Pim\Enrichment\Component\Product\Normalizer\Standard\GroupNormalizer;
use Prophecy\Argument;

class GroupNormalizerSpec extends ObjectBehavior
{
    function let(
        GroupNormalizer $groupNormalizerStandard,
        TranslationNormalizer $translationNormalizer
    ) {
        $this->beConstructedWith($groupNormalizerStandard, $translationNormalizer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Akeneo\Tool\Bundle\VersioningBundle\Normalizer\Flat\GroupNormalizer');
    }

    function it_is_a_normalizer()
    {
        $this->shouldImplement('Symfony\Component\Serializer\Normalizer\NormalizerInterface');
    }

    function it_only_supports_flat_normalization_of_group(GroupInterface $group)
    {
        $this->supportsNormalization($group, 'flat')->shouldReturn(true);
        $this->supportsNormalization($group, 'csv')->shouldReturn(false);
        $this->supportsNormalization($group, 'flat')->shouldReturn(true);
        $this->supportsNormalization($group, 'json')->shouldReturn(false);
    }

    function it_normalizes_a_group(
        GroupNormalizer $groupNormalizerStandard,
        TranslationNormalizer $translationNormalizer,
        GroupInterface $group
    ) {
        $translationNormalizer->supportsNormalization(Argument::cetera())->willReturn(true);
        $translationNormalizer->normalize(Argument::cetera(), 'flat', [])->willReturn([]);

        $groupNormalizerStandard->supportsNormalization($group, 'standard')->willReturn(true);
        $groupNormalizerStandard->normalize($group, 'standard', [])->willReturn(
            [
                'code'   => 'mugs',
                'type'   => 'RELATED',
                'labels' => [],
            ]
        );

        $this->normalize($group, 'flat', [])->shouldReturn(
            [
                'code'   => 'mugs',
                'type'   => 'RELATED',
            ]
        );
    }
}

