<?php
/**
 * @file
 *
 */

/**
 * Items layer class
 */
class VoxbItems extends VoxbBase {

  protected $items = array();
  protected $reviews;
  protected $reviewHandlers = array();

  /**
   * Create VoxbItems object.
   */
  public function __construct() {
    parent::getInstance();
  }

  /**
   * Get VoxB data by FAUST identifiers.
   *
   * @param array $faust_nums
   *   FAUST numbers of items.
   *
   * @return bool
   *   TRUE in case of success.
   *   FALSE if an error occurred.
   */
  public function fetchByFaust($faust_nums) {
    return $this->fetchBy($faust_nums, 'FAUST');
  }

  /**
   * Get VoxB data by ISBN identifiers.
   *
   * @param array $ids
   *   ISBN numbers of items.
   *
   * @return bool
   *   TRUE in case of success.
   *   FALSE if an error occurred.
   */
  public function fetchByISBN($ids) {
    return $this->fetchBy($ids, 'ISBN');
  }

  /**
   * Fetch VoxB data for items.
   *
   * @param array $ids
   *   See voxb:objectIdentifierValue.
   * @param string $type
   *   See voxb:objectIdentifierType.
   *
   * @return bool
   *   TRUE in case of success.
   *   FALSE if an error occurred.
   */
  protected function fetchBy($ids, $type) {
    $fetch = array();
    $type = strtoupper($type);
    $ids = array_unique($ids);

    foreach ($ids as $id) {
      $item = array(
        'objectIdentifierValue' => $id,
        'objectIdentifierType' => $type,
      );
      if (self::$instance->getVersion() == '1.2') {
        $item['institutionId'] = variable_get('voxb_institution_id', '');
      }
      $fetch[] = $item;
    }

    $data = array(
      'fetchData' => $fetch,
      'output' => array('contentType' => 'all'),
    );

    $this->reviews = new VoxbReviewsController();

    $o = $this->call('fetchData', $data);

    if (!empty($o->error)) {
      ding_voxb_log(
        WATCHDOG_ERROR,
        'IDs: @ids. Error: @error',
        array('@ids' => implode(',', $ids), '@error' => $o->error)
      );

      return FALSE;
    }

    if (!empty($o->totalItemData)) {
      if (is_array($o->totalItemData)) {
        $id = NULL;

        foreach ($o->totalItemData as $v) {
          $id = (string) $v->fetchData->objectIdentifierValue;

          $item = new VoxbItem();
          $item->addReviewHandler('review', new VoxbReviews());
          $item->fetchData($v);

          $this->items[$id] = $item;
        }
      }
      else {
        $id = (string) $o->totalItemData->fetchData->objectIdentifierValue;

        $item = new VoxbItem();
        $item->addReviewHandler('review', new VoxbReviews());
        $item->fetchData($o->totalItemData);

        $this->items[$id] = $item;
      }
    }

    return TRUE;
  }

  /**
   * Get VoxbItem object by its identifier.
   *
   * @param string $id
   *   Object identifier.
   *
   * @return object|bool
   *   VoxbItem object or FALSE.
   */
  public function getItem($id) {
    if (isset($this->items[$id])) {
      return $this->items[$id];
    }

    return FALSE;
  }

  /**
   * Get amount of items in the layer.
   *
   * @return int
   *   Number of items.
   */
  public function getCount() {
    return count($this->items);
  }

  /**
   * Add review handlers to factory.
   *
   * @param string $name
   *   Handler name.
   * @param object $object
   *   Object.
   */
  public function addReviewHandler($name, $object) {
    $this->reviewHandlers[$name] = $object;
  }
}
