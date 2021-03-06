<?php
/**
 * @file
 *
 * VoxbReviews class.
 * This class handles reviews colection.
 */

class VoxbReviews extends VoxbBase implements Iterator, Countable {

  private $items = array();
  private $position;

  public function __construct() {
    parent::getInstance();
    $this->position = 0;
  }

  public function fetch($voxbUserItems) {
    foreach ($voxbUserItems as $v) {
      /**
       * Select only reviews that are marked as review
       * This line can be changes, depends on the changes done on the server side
       */
      if (isset($v->review) && $v->review->reviewTitle == 'review') {
        $this->items[] = new VoxbReviewRecord($v);
      }
    }
  }

  /**
   * This method takes all items attributes and coverts them to an array.
   *
   * @return array
   */
  public function toArray() {
    $a = array();
    foreach ($this->items as $v) {
      $a[] = $v->toArray();
    }
    return $a;
  }

  /**
   * Iterator interface method.
   */
  public function rewind() {
    $this->position = 0;
  }

  /**
   * Iterator interface method.
   */
  public function current() {
    return $this->items[$this->position];
  }

  /**
   * Iterator interface method.
   */
  public function key() {
    return $this->position;
  }

  /**
   * Iterator interface method.
   */
  public function next() {
    ++$this->position;
  }

  /**
   * Iterator interface method.
   */
  public function valid() {
    return isset($this->items[$this->position]);
  }

  /**
   * Returns amount of items available in class collection.
   *
   * @return integer
   */
  public function getCount() {
    return count($this->items);
  }

  /**
   * Implementation of Countable interface.
   *
   * @return integer
   */
  public function count() {
    return $this->getCount();
  }
}
