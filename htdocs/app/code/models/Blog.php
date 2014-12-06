<?php

namespace App\Code\Models;

use App\Code\App;
use App\Code\ModelAdapter;

class Blog extends ModelAdapter
{

    protected $tableAbbr = 'bl';

    protected $postCollection;

    protected $user;

    public function selectBlogPosts($userId = 8, $limit = 5, $offset = 0)
    {
        /** @var ModelAdapter $blogPosts */
        $blogPosts = App::getModel('BlogPosts');
        $blogPosts->setLimit($limit)
            ->setOffset($offset)
            ->setOrderBy('post_date', 'desc')
            ->setWhere($blogPosts->getField('post_schedule'), 'CURRENT_DATE()', '<=')
            ->setOn($this->getField('id'), $blogPosts->getField('blog_id'));

        /** @var ModelAdapter $users */
        $users = App::getModel('Users');

        $users->setOn($users->getField('id'), $this->getField('user_id'));

        $this->leftJoin($blogPosts)->leftJoin($users);

        $this->setWhere($this->getField('user_id'), $userId);
        $collection = $this->runLeftJoinQuery();

        if(!empty($collection)) {

            $this->setData($collection[0],true);
            $this->user = $users->create()->setData($collection[0],true);
            foreach ($collection as $records) {
                $object = $blogPosts->create();
                $this->postCollection[] = $object->setData($records,true);
            }
        }
        return $this;
    }

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
}