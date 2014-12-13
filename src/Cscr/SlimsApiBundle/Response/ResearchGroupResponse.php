<?php

namespace Cscr\SlimsApiBundle\Response;

use JMS\Serializer\Annotation as JMS;

class ResearchGroupResponse extends ExtJsResponse
{
    /**
     * @var
     *
     * @JMS\SerializedName("groups")
     */
    protected $data;
}
