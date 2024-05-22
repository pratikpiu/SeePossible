<?php

namespace SeePossible\Blog\Api\Data;

/**
 * Interface BlogInterface
 * @package SeePossible\Blog\Api\Data
 */
interface BlogInterface
{
    const POST_ID = 'post_id';

    const TITLE = 'title';

    const DISCRIPTION = 'discription';

    const IS_ACTIVE = 'is_active';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';



    /**
     * Get  ID
     *
     * @return int
     */
    public function getPostId();

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Get Discription
     *
     * @return string
     */
    public function getDiscription();

    /**
     * Get Is Active
     *
     * @return int
     */
    public function getIsActive();

    /**
     * Get Create At
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Get Updated At
     *
     * @return string
     */
    public function getUpdatedAt();

    /**
     * Set ID
     *
     * @param int $postId
     * @return $this
     */
    public function setPostId(int $postId);

    /**
     * Set Title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Set discription
     *
     * @param string $discription
     * @return $this
     */
    public function setDiscription($discription);


    /**
     * Set Is Active
     *
     * @param int $isActive
     * @return $this
     */
    public function setIsActive($isActive);

    /**
     * Set Created At
     *
     * @param string $createAt
     * @return $this
     */
    public function setCreatedAt($createAt);

    /**
     * Set Updated At
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);
}
