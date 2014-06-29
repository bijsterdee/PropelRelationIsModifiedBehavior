<?php

namespace RelationIsModifiedBehavior\Tests;

use RelationIsModifiedBehavior\Model\Article;
use RelationIsModifiedBehavior\Model\Comment;
use RelationIsModifiedBehavior\Model\Page;
use RelationIsModifiedBehavior\Model\PageArticle;


class RelationIsModifiedBehaviorTest extends \PHPUnit_Framework_TestCase
{
    public function testRegularFk()
    {
        $article = new Article();
        $this->assertFalse($article->isModified());

        $article->setTitle('New article');
        $this->assertTrue($article->isModified());

        $article->clear();

        $comment = new Comment();
        $this->assertFalse($comment->isModified());

        $comment->setName('Bezoeker');
        $this->assertTrue($comment->isModified());

        $comment->setArticle($article);
        $this->assertFalse($article->isModified());
        $this->assertTrue($article->isModified(true));
    }

    public function testCrossrefFk()
    {
        $article = new Article();
        $page = new Page();

        $article->addPage($page);
        $this->assertFalse($article->isModified());
        $this->assertTrue($article->isModified(true));
    }

    public function testCrossrefFkFromCrossrefTable()
    {
        $page = new Page();
        $page_article = new PageArticle();

        $page_article->setPage($page);

        $this->assertFalse($page_article->isModified());
        $this->assertTrue($page_article->isModified(true));
    }
}