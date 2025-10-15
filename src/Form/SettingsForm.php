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
      '#description' => $this->t('Turn on debug mode'),
      '#return_value' => 1,
    ];

    $form['attributes'] = [
      '#markup' => $this->getDebugAttributes(),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('ubc_cwl_auth.settings')
      ->set('ubc_cwl_auth_debug', $form_state->getValue('debug'))
      ->save();

    \Drupal::logger('ubc_cwl_auth')->notice('UBC_CWL_AUTH Debug Setting: '.$form_state->getValue('debug'));

    parent::submitForm($form, $form_state);
  }

  private function getDebugAttributes() {

    //\Drupal::logger('samlauth')->debug('Test SAML debug message from test');

    return '<p>TODO: fetch data from Cache</p>';
  }

}
