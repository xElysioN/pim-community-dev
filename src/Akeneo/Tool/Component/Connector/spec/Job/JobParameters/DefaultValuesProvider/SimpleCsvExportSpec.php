<?php

namespace spec\Akeneo\Tool\Component\Connector\Job\JobParameters\DefaultValuesProvider;

use Akeneo\Tool\Component\Batch\Job\JobInterface;
use PhpSpec\ObjectBehavior;

class SimpleCsvExportSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(['my_supported_job_name']);
    }

    function it_is_a_provider()
    {
        $this->shouldImplement('Akeneo\Tool\Component\Batch\Job\JobParameters\DefaultValuesProviderInterface');
    }

    function it_provides_default_values()
    {
        $this->getDefaultValues()->shouldReturn(
            [
                'filePath'   => sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'export_%job_label%_%datetime%.csv',
                'delimiter'  => ";",
                'enclosure'  => '"',
                'withHeader' => true,
                'user_to_notify' => null,
                'is_user_authenticated' => false,
            ]
        );
    }

    function it_supports_a_job(JobInterface $job)
    {
        $job->getName()->willReturn('my_supported_job_name');
        $this->supports($job)->shouldReturn(true);
    }
}
