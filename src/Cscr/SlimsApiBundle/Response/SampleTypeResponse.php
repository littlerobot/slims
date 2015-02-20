<?php

namespace Cscr\SlimsApiBundle\Response;

use JMS\Serializer\Annotation as JMS;

class SampleTypeResponse extends ExtJsResponse
{
    /**
     * @var
     *
     * @JMS\SerializedName("sample_type")
     */
    protected $data;
}
