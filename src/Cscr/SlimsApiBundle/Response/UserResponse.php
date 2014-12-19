<?php

namespace Cscr\SlimsApiBundle\Response;

use JMS\Serializer\Annotation as JMS;

class UserResponse extends ExtJsResponse
{
    /**
     * @var
     *
     * @JMS\SerializedName("users")
     */
    protected $data;
}
