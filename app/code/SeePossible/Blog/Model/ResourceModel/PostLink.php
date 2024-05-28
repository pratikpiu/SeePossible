<?php

namespace SeePossible\Blog\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class PostLink
 * @package SeePossible\Blog\Model\ResourceModel
 */
class PostLink extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('blog_post_product_link', 'link_id');
    }

    /**
     * @param $linkId
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteBlogPostLink($linkId)
    {
        return $this->getConnection()->delete($this->getMainTable(), ['link_id = ?' => $linkId]);
    }

    /**
     * @param $parentId
     * @param $linkedBlogPostId
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBlogPostLinkId($parentId, $linkedBlogPostId)
    {
        $connection = $this->getConnection();

        $bind = [
            ':product_id' => (int)$parentId,
            ':linked_blog_post_id' => (int)$linkedBlogPostId
        ];
        $select = $connection->select()->from(
            $this->getMainTable(),
            ['link_id']
        )->where(
            'product_id = :product_id'
        )->where(
            'linked_blog_post_id = :linked_blog_post_id'
        );

        return $connection->fetchOne($select, $bind);
    }

    /**
     * @param $parentId
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBlogPostLinks($parentId)
    {
        $connection = $this->getConnection();

        $bind = [
            ':product_id' => (int)$parentId,
        ];
        $select = $connection->select()->from(
            $this->getMainTable(),
            ['link_id', 'product_id', 'linked_blog_post_id','position']
        )->where(
            'product_id = :product_id'
        )->order('position', 'ASC');

        return  $connection->fetchAll($select, $bind);
    }

    /**
     * @param $parentId
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function hasBlogPostLinks($parentId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getMainTable(),
            ['count' => new \Zend_Db_Expr('COUNT(*)')]
        )->where(
            'product_id = :product_id'
        );

        return $connection->fetchOne(
                $select,
                [
                    'product_id' => $parentId
                ]
            ) > 0;
    }

    /**
     * @param $parentId
     * @param $data
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function saveBlogPostLinks($parentId, $data)
    {
        if (!is_array($data)) {
            $data = [];
        }

        $connection = $this->getConnection();

        $bind = [':product_id' => (int)$parentId];
        $select = $connection->select()->from(
            $this->getMainTable(),
            ['linked_blog_post_id', 'link_id', 'position']
        )->where(
            'product_id = :product_id'
        );

        $links = $connection->fetchPairs($select, $bind);
        return $this->prepareBlogPostLinksData($parentId, $data, $links);

    }

    /**
     * @param $parentId
     * @param $data
     * @param $links
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function prepareBlogPostLinksData($parentId, $data, $links)
    {
        $connection = $this->getConnection();

        foreach ($data as $linkedBlogPostId => $linkInfo) {
            $linkId = null;
            if (isset($links[$linkedBlogPostId])) {
                $linkId = $links[$linkedBlogPostId];
            } else {
                $bind = [
                    'product_id' => $parentId,
                    'linked_blog_post_id' => $linkedBlogPostId,
                    'position' => $linkInfo['position']
                ];
                $connection->insert($this->getMainTable(), $bind);
                $linkId = $connection->lastInsertId($this->getMainTable());
            }
        }

        return true;
    }

    /**
     * @param $parentId
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBlogPostByProduct($parentId)
    {
        $connection = $this->getConnection();

        $bind = [
            ':product_id' => (int)$parentId,
        ];
        $select = $connection->select()->from(
            ['tp' => $this->getMainTable()]
        )->join(['t' => 'sp_blog'], 'tp.linked_blog_post_id = t.post_id')
            ->where('tp.product_id = :product_id')
            ->where('t.status = 1')
            ->order('position', 'ASC');

        return $connection->fetchAll($select, $bind);
    }

}
