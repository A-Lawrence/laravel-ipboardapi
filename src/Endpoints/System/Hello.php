<?php

namespace Alawrence\Ipboard;

trait Hello
{
    /**
     * Call core/hello to find details of forum instance.
     *
     * @throws \Alawrence\Ipboard\Exceptions\IpboardInvalidApiKey
     * @throws \Alawrence\Ipboard\Exceptions\IpboardThrottled
     * @throws \Alawrence\Ipboard\Exceptions\IpboardMemberIdInvalid
     *
     * @return string json return.
     */
    public function hello()
    {
        return $this->getRequest('core/hello');
    }
}
