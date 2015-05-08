<?php

namespace Cscr\SlimsApiBundle\Response;

use JMS\Serializer\Annotation as JMS;

class SamplesResponse extends ExtJsResponse
{
    /**
     * @var
     *
     * @JMS\SerializedName("samples")
     */
    protected $data;
}
