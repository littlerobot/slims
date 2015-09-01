<?php

namespace Cscr\SlimsApiBundle\Response;

use Cscr\SlimsApiBundle\Entity\ResearchGroup;
use JMS\Serializer\Annotation as JMS;

class ResearchGroupResponse extends ExtJsResponse
{
    /**
     * @var ResearchGroup
     *
     * @JMS\SerializedName("group")
     */
    protected $data;

    /**
     * @param ResearchGroup $group
     */
    public function __construct(ResearchGroup $group)
    {
        parent::__construct($group);
    }
}
