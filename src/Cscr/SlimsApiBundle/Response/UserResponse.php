<?php

namespace Cscr\SlimsApiBundle\Response;

use Cscr\SlimsUserBundle\Entity\User;
use JMS\Serializer\Annotation as JMS;

class UserResponse extends ExtJsResponse
{
    /**
     * @var User
     *
     * @JMS\SerializedName("user")
     */
    protected $data;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
    }
}
