<?php

namespace Drupal\ubc_cwl_auth\Debug;

use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Reactor service — keep this light; push heavy work to queue/worker.
 *
 * Note: this class purposely does NOT inject logger.factory to avoid a
 * circular dependency with the logger.factory decorator. It obtains a logger
 * at runtime inside handleDebugMessage().
 */
class SamlauthDebugReactor {

  protected ConfigFactoryInterface $configFactory;

  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * Handle a debug message from the samlauth channel.
   *
   * Keep this method light; if heavy work is required push to the queue.
   *
   * @param string $message
   * @param array $context
   */
  public function handleDebugMessage(string $message, array $context = []) : void {
    // Obtain the logger at runtime to avoid container circular references.
    $logger = \Drupal::logger('ubc_cwl_auth');

    if (strpos($message, 'SAML') !== FALSE || TRUE) {

      $config = $this->configFactory->get('ubc_cwl_auth.settings');

      if($config->get('ubc_cwl_auth_debug') == 1) {

        $cid = time();
        \Drupal::cache('ubc_cwl_auth')->set($cid, $message, (time() + 24*60*60));
      }

    }
  }
}
