<?php

namespace App\Code\Models;

use App\Code\App;
use App\Code\ModelAdapter;

class BlogPosts extends ModelAdapter
{

    protected $tableAbbr = 'bp';

    protected $postCollection;

    protected $commentCollection;

    protected $user;

    protected $blog;

    /**
     * @return mixed
     */
    public function getPostCollection()
    {
        return $this->postCollection;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getCommentCollection()
    {
        return $this->commentCollection;
    }

    /**
     * @return mixed
     */
    public function getBlog()
    {
        return $this->blog;
    }

    public function selectBlogPosts($limit = 0, $offset = 0)
    {

        $this->setRowsCount($this->getPostsCount());

        $offset = ($offset - 1) * $limit;

        if ($offset < 0) {
            $offset = 0;
        }

        $this->setLimit($limit)
            ->setOffset($offset)
            ->setOrderBy('post_date', 'desc')
            ->setWhere($this->getField('post_schedule'), 'CURRENT_DATE()', '<=')
            ->setOn($this->getField('id'), $this->getField('blog_id'));

        /** @var ModelAdapter $users */
        $users = App::getModel('Users');
        $users->setOn($users->getField('id'), $this->getField('user_id'));
        /** @var Blog $blogPosts */
        $blog = App::getModel('Blog');
        $blog->setOn($blog->getField('id'), $this->getField('blog_id'));

        $this->leftJoin($blog)->leftJoin($users);

        $collection = $this->runLeftJoinQuery();

        if (!empty($collection)) {

            $this->blog = $blog->setData($collection[0], true);

            foreach ($collection as $records) {
                $object = $this->create()
                    ->setData($records, true);
                $object->user = $users->create()->setData($records, true);
                $this->postCollection[] = $object;
            }
        }

        return $this;
    }

    public function getPostsCount()
    {
        return $this->selectAllBy(array(
                'field' => 'post_schedule', 'value' => 'CURRENT_DATE()',
                'opt' => '<=')
            , true, 'post_date');
    }

    public function selectBlogPostBySlugTag($slugTag = null, $limit = 0)
    {
        $users = App::getModel('Users');
        $users->setOn($users->getField('id'), $this->getField('user_id'));

        $comments = App::getModel('BlogComments');
        $comments->setOn(
            $this->getField('id'), $comments->getField('post_id')
        )->setLimit($limit)->setOrderBy('comment_date', 'asc');

        $this->setWhere(
            $this->getField('post_slug_tag'),
            $this->dbAdapter->quote($slugTag)
        );

        $this->leftJoin($users)->leftJoin($comments);

        $collection = $this->runLeftJoinQuery();

        if (!empty($collection)) {

            $this->setData($collection[0], true);
            $this->user = $users->setData($collection[0], true);

            foreach ($collection as $records) {
                /** @var BlogComments $object */
                $object = $comments->create()
                    ->setData($records, true);
                if ($object->getId() != null) {
                    $this->commentCollection[] = $object;
                }
            }
        }

        return $this;
    }
}