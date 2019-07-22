<?php

namespace Alawrence\Ipboard;

use Alawrence\Ipboard\Exceptions\IpboardThrottled;
use Alawrence\Ipboard\Exceptions\IpboardInvalidApiKey;
use Alawrence\Ipboard\Exceptions\IpboardMemberIdInvalid;

trait Posts
{
    /**
     * Fetch all forum posts that match the given search criteria.
     *
     * @param array $searchCriteria The search criteria posts should match.
     * @param int   $page           The page number to retrieve (default 1).
     *
     * @throws Exceptions\IpboardMemberEmailExists
     * @throws Exceptions\IpboardMemberInvalidGroup
     * @throws Exceptions\IpboardMemberUsernameExists
     * @throws IpboardInvalidApiKey
     * @throws IpboardMemberIdInvalid
     * @throws IpboardThrottled
     * @throws Exceptions\InvalidFormat
     * @throws \Exception
     *
     * @return mixed
     */
    public function getForumPostsByPage($searchCriteria, $page = 1)
    {
        $validator = \Validator::make($searchCriteria, [
            'forums'        => 'string|is_csv_numeric',
            'authors'       => 'string|is_csv_numeric',
            'hasBestAnswer' => 'in:1,0',
            'hasPoll'       => 'in:1,0',
            'locked'        => 'in:1,0',
            'hidden'        => 'in:1,0',
            'pinned'        => 'in:1,0',
            'featured'      => 'in:1,0',
            'archived'      => 'in:1,0',
            'sortBy'        => 'in:id,date,title',
            'sortDir'       => 'in:asc,desc',
        ], [
            'is_csv_numeric' => 'The :attribute must be a comma separated string of IDs.',
        ]);

        if ($validator->fails()) {
            $message = head(array_flatten($validator->messages()));

            throw new Exceptions\InvalidFormat($message);
        }

        return $this->getRequest('forums/posts', array_merge($searchCriteria, ['page' => $page]));
    }

    /**
     * Fetch all forum posts that match the given search criteria.
     *
     * @param array $searchCriteria The search criteria posts should match.
     *
     * @throws Exceptions\InvalidFormat
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
    public function getForumPostsAll($searchCriteria)
    {
        $allPosts = [];

        $currentPage = 1;
        do {
            $response = $this->getForumPostsByPage($searchCriteria, $currentPage);
            $allPosts = array_merge($allPosts, $response->results);
            $currentPage++;
        } while ($currentPage <= $response->totalPages);

        return $allPosts;
    }

    /**
     * Get a specific forum post given the ID.
     *
     * @param int $postId The ID of the forum post to retrieve.
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
    public function getForumPostById($postId)
    {
        return $this->getRequest('forums/posts/'.$postId);
    }

    /**
     * Create a forum post with the given data.
     *
     * @param int    $topicID  The ID of the topic to add the post to.
     * @param int    $authorID The ID of the author for the post (if set to 0, author_name is used)
     * @param string $post     The HTML content of the post.
     * @param array  $extra
     *
     * @throws Exceptions\InvalidFormat
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
    public function createForumPost($topicID, $authorID, $post, $extra = [])
    {
        $data = ['topic' => $topicID, 'author' => $authorID, 'post' => $post];
        $data = array_merge($data, $extra);

        $validator = \Validator::make($data, [
            'topic'       => 'required|numeric',
            'author'      => 'required|numeric',
            'post'        => 'required|string',
            'author_name' => 'required_if:author,0|string',
            'date'        => 'date_format:YYYY-mm-dd H:i:s',
            'ip_address'  => 'ip',
            'hidden'      => 'in:-1,0,1',
        ]);

        if ($validator->fails()) {
            $message = head(array_flatten($validator->messages()));

            throw new Exceptions\InvalidFormat($message);
        }

        return $this->postRequest('forums/posts', $data);
    }

    /**
     * Update a forum post with the given ID.
     *
     * @param $postId
     * @param array $data The data to edit.
     *
     * @throws Exceptions\InvalidFormat
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
    public function updateForumPost($postId, $data = [])
    {
        $validator = \Validator::make($data, [
            'author'      => 'numeric',
            'author_name' => 'required_if:author,0|string',
            'post'        => 'string',
            'hidden'      => 'in:-1,0,1',
        ]);

        if ($validator->fails()) {
            $message = head(array_flatten($validator->messages()));

            throw new Exceptions\InvalidFormat($message);
        }

        return $this->postRequest('forums/posts/'.$postId, $data);
    }

    /**
     * Delete a forum post given it's ID.
     *
     * @param int $postId The ID of the post to delete.
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
    public function deleteForumPost($postId)
    {
        return $this->deleteRequest('forums/posts/'.$postId);
    }
}
