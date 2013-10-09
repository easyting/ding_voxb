<?php
/**
 * @file
 *
 * Admin pages for the Ding VoxB module.
 */

/**
 * Settings form.
 */
function ding_voxb_admin($form, $form_state) {
  $form = array();

  $form['voxb'] = array(
    '#type' => 'fieldset',
    '#title' => t('VoxB service settings'),
  );

  $form['voxb']['voxb_service_url'] = array(
    '#type' => 'textfield',
    '#title' => t('Service URL'),
    '#description' => t('URL to the VoxB webservice, e.g. https://voxb.addi.dk/1.1/voxb.wsdl'),
    '#default_value' => variable_get('voxb_service_url', ''),
    '#required' => TRUE,
  );

  $form['voxb']['voxb_identity_provider'] = array(
    '#type' => 'textfield',
    '#title' => t('Identity Provider'),
    '#description' => t('Identity provider for the service, e.g. Testbibliotek'),
    '#required' => TRUE,
    '#default_value' => variable_get('voxb_identity_provider', ''),
  );

  $form['voxb']['voxb_institution_id'] = array(
    '#type' => 'textfield',
    '#title' => t('Institution id'),
    '#description' => t('Institution id, e.g. 1'),
    '#required' => TRUE,
    '#default_value' => variable_get('voxb_institution_id', ''),
  );

  $form['voxb']['voxb_reviews_per_page'] = array(
    '#type' => 'textfield',
    '#title' => t('Reviews per page'),
    '#description' => t('Text reviews shown per page.'),
    '#required' => TRUE,
    '#default_value' => variable_get('voxb_reviews_per_page', VOXB_DEFAULT_REVIEWS_PER_PAGE),
  );

  $form['voxb']['voxb_logging'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable logging'),
    '#default_value' => variable_get('voxb_logging', 0),
    '#description' => t("Log all errors to watchdog."),
  );

  $form['#submit'] = array('ding_voxb_admin_submit');

  return system_settings_form($form);
}

/**
 * Custom validation handler for settings form.
 *
 * @see ding_voxb_admin(),
 */
function ding_voxb_admin_validate($form, &$form_state) {
  $input = $form_state['input'];

  if (!is_numeric($input['voxb_institution_id'])) {
    form_set_error('voxb_institution_id', t('This field requires a numeric value.'));
  }
}

/**
 * Custom validation handler for settings form.
 *
 * @see ding_voxb_admin(),
 */
function ding_voxb_admin_submit($form, &$form_state) {
  drupal_set_message(t('You must log out to changes take effect'), 'warning');

  // Clear all item list cache.
  module_invoke('ding_item_cache', 'clear_all');
}
