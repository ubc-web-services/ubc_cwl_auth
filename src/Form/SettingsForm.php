<?php

namespace Drupal\ubc_cwl_auth\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the settings form
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['ubc_cwl_auth.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ubc_cwl_auth_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('ubc_cwl_auth.settings');

    $form['debug'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('UBC CWL Debug Mode'),
      '#default_value' => $config->get('ubc_cwl_auth_debug'),
      '#description' => $this->t('Turn on debug mode. Log SAML responses.'),
      '#return_value' => 1,
    ];

    $form['attr1'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Attribute 1'),
      '#default_value' => $config->get('ubc_cwl_auth_attr1'),
    ];

    $form['attr2'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Attribute 2'),
      '#default_value' => $config->get('ubc_cwl_auth_attr2'),
    ];

    $form['attr3'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Attribute 3'),
      '#default_value' => $config->get('ubc_cwl_auth_attr3'),
    ];

    $form['attr4'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Attribute 4'),
      '#default_value' => $config->get('ubc_cwl_auth_attr4'),
    ];

    $form['attr5'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Attribute 5'),
      '#default_value' => $config->get('ubc_cwl_auth_attr5'),
    ];

    $form['attributes'] = [
      '#markup' => $this->getDebugAttributes($config),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('ubc_cwl_auth.settings')
      ->set('ubc_cwl_auth_debug', $form_state->getValue('debug'))
      ->set('ubc_cwl_auth_attr1', $form_state->getValue('attr1'))
      ->set('ubc_cwl_auth_attr2', $form_state->getValue('attr2'))
      ->set('ubc_cwl_auth_attr3', $form_state->getValue('attr3'))
      ->set('ubc_cwl_auth_attr4', $form_state->getValue('attr4'))
      ->set('ubc_cwl_auth_attr5', $form_state->getValue('attr5'))
      ->save();

    \Drupal::logger('ubc_cwl_auth')->notice('UBC_CWL_AUTH Debug Setting: '.$form_state->getValue('debug'));

    parent::submitForm($form, $form_state);
  }

  /**
   * Fetch rows from the cache_ubc_cwl_auth table and display
   */
  private function getDebugAttributes($config) {

    if($config->get('ubc_cwl_auth_debug') != 1) {
      return '';
    }

    $database = \Drupal::database();
    $query = $database->select('cache_ubc_cwl_auth', 'c')
      ->fields('c', ['cid', 'data']);
    $result = $query->execute()->fetchAll();

    $table = '<table><thead><tr><th>CID</th><th>DATA</th></tr></thead>';
    foreach ($result as $item) {
      $cid = $item->cid;
      $data = json_decode($item->data);
      $data = (array)$data;

      $table .= '<tr><td>'.$cid.'</td><td>'.print_r($data, true).'</td></tr>';
    }
    $table .= '</table>';

    return $table;
  }


}
