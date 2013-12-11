<?php
/**
 * @file
 * Base VoxB-client class.
 *
 * Singleton class, supports connection to VoxB server.
 */

class VoxbBase {

  /**
   * Singleton template attribute.
   *
   * @var object
   */
  public static $instance = NULL;

  /**
   * SOAP client attribute.
   *
   * @var object
   */
  public static $soapClient = NULL;

  protected $serviceVersion;

  /**
   * Constructor initialize $this->soapClient attribute.
   */
  private function __construct() {
    $service_url = variable_get('voxb_service_url', '');

    if (empty($service_url)) {
      return FALSE;
    }

    $url = parse_url($service_url);
    $url = explode('/', $url['path']);
    foreach ($url as $part) {
      if (preg_match('/([0-9]+\.[0-9]+)/', $part)) {
        $this->serviceVersion = $part;
        break;
      }
    }

    $options = array(
      'soap_version' => SOAP_1_2,
      'exceptions' => TRUE,
      'trace' => 1,
      'cache_wsdl' => WSDL_CACHE_NONE,
      'namespaces' => array(
        'voxb' => 'http://oss.dbc.dk/ns/voxb',
      ),
    );

    try {
      VoxbBase::$soapClient = new SoapClient(variable_get('voxb_service_url', ''), $options);
    }
    catch (Exception $e) {
      ding_voxb_log(
        WATCHDOG_DEBUG,
        "NanoSOAPClient caused error: @error",
        array('@error' => $e->getMessage())
      );
      VoxbBase::$soapClient = NULL;
    }
  }

  /**
   * Singleton feature.
   */
  public static function getInstance() {
    if (VoxbBase::$instance == NULL) {
      VoxbBase::$instance = new VoxbBase();
    }
    return VoxbBase::$instance;
  }

  /**
   * Use this method to call VoxB server methods.
   *
   * @param string $method
   *   Method name.
   * @param array $data
   *   Data to be passed to method.
   *
   * @return mixed
   *   Service response.
   */
  public function call($method, $data) {
    $response = FALSE;

    if (VoxbBase::$soapClient == NULL) {
      ding_voxb_log(WATCHDOG_ERROR, 'No SOAP client');
      return $response;
    }

    try {
      timer_start('voxb');

      $response = VoxbBase::$soapClient->$method($data);

      timer_stop('voxb');
    }
    catch (Exception $e) {
      ding_voxb_log(
        WATCHDOG_ERROR,
        "Calling @method returned error: @error",
        array('@method' => $method, '@error' => $e->getMessage())
      );
    }

    return $response;
  }

  /**
   * Check if the service is available.
   */
  public function isServiceAvailable() {
    return (VoxbBase::$soapClient == NULL ? FALSE : TRUE);
  }

  /**
   * Get service version.
   *
   * @return string
   *   Service version.
   */
  public function getVersion() {
    return $this->serviceVersion;
  }
}
