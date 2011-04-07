<?php
require_once(dirname(__FILE__) . '/VoxbTest.php');

/**
 * @file
 */

class VoxbItemTest extends VoxbTest {

  public function testRating() {
    $obj = new VoxbItem();
    $obj->fetchByFaust('111111111');
    $this->assertEquals($obj->getRating(), 55);
    $this->assertEquals($obj->getRatingCount(), 4);
  }

  public function testTagsList() {
    $obj = new VoxbItem();
    $obj->fetchByFaust('111111111');
    $this->assertNotNull($obj->getTags());
    $this->assertEquals($obj->getTags()->getCount(), 4);
    $tags = $obj->getTags()->toArray();
    $this->assertTrue(is_array($tags));
    $this->assertEquals(4, count($tags));

    foreach ($obj->getTags() as $v) {
      if ($v->getName() == 'tag4') {
        $this->assertEquals($v->getCount(), 3);
      }
      elseif ($v->getName() == 'tag1') {
        $this->assertEquals($v->getCount(), 2);
      }
      else {
        $this->assertEquals($v->getCount(), 1);
      }
    }
  }

  public function testCommentsList() {
    $obj = new VoxbItem();
    $obj->addReviewHandler('comment', new VoxbComments());
    $obj->fetchByFaust('111111111');

    $this->assertNotNull($obj->getReviews('comment'));
    $comment = $obj->getReviews('comment')->toArray();

    $this->assertEquals($obj->getReviews('comment')->getCount(), 3);
    $this->assertTrue(is_array($comment));
  }
}

