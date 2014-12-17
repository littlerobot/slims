<?php

namespace Cscr\SlimsApiBundle\Response;

use JMS\Serializer\Annotation as JMS;

class UserCollectionResponse extends ExtJsResponse
{
    /**
     * @var
     *
     * @JMS\SerializedName("users")
     */
    protected $data;
}
