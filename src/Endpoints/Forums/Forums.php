<?php

namespace Alawrence\Ipboard;

use Alawrence\Ipboard\Exceptions\IpboardThrottled;
use Alawrence\Ipboard\Exceptions\IpboardInvalidApiKey;
use Alawrence\Ipboard\Exceptions\IpboardMemberIdInvalid;

trait Forums
{
    /**
     * Fetch all forums.
     *
     * @throws Exceptions\IpboardMemberEmailExists
     * @throws Exceptions\IpboardMemberInvalidGroup
     * @throws Exceptions\IpboardMemberUsernameExists
     * @throws IpboardInvalidApiKey
     * @throws IpboardMemberIdInvalid
     * @throws IpboardThrottled
     * @throws \Exception
     *
     * @return mixed
     */
    public function getForumsAll()
    {
        return $this->getRequest('forums/forums');
    }

    /**
     * Get a specific forum given the ID.
     *
     * @param int $forumId The ID of the forum post to retrieve.
     *
     * @throws Exceptions\IpboardMemberEmailExists
     * @throws Exceptions\IpboardMemberInvalidGroup
     * @throws Exceptions\IpboardMemberUsernameExists
     * @throws IpboardInvalidApiKey
     * @throws IpboardMemberIdInvalid
     * @throws IpboardThrottled
     * @throws \Exception
     *
     * @return mixed
     */
    public function getForumById($forumId)
    {
        return $this->getRequest('forums/forums/'.$forumId);
    }
}
