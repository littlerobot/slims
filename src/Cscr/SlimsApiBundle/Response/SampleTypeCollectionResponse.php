<?php

namespace Cscr\SlimsApiBundle\Response;

use JMS\Serializer\Annotation as JMS;

class SampleTypeCollectionResponse extends ExtJsResponse
{
    /**
     * @var
     *
     * @JMS\SerializedName("sample_types")
     */
    protected $data;
}
