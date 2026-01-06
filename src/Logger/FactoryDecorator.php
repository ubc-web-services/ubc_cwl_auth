<?php

namespace Drupal\ubc_cwl_auth\Logger;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Psr\Log\LoggerInterface;
use Drupal\ubc_cwl_auth\Debug\SamlauthDebugReactor;

/**
 * Decorates the logger.factory service to intercept samlauth debug logs.
 */
class FactoryDecorator implements LoggerChannelFactoryInterface {

  /**
   * The original logger factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $innerFactory;

  /**
   * The reactor service.
   *
   * @var \Drupal\ubc_cwl_auth\Debug\SamlauthDebugReactor
   */
  protected $reactor;

  /**
   * Constructs a new FactoryDecorator.
   */
  public function __construct(LoggerChannelFactoryInterface $inner_factory, SamlauthDebugReactor $reactor) {
    $this->innerFactory = $inner_factory;
    $this->reactor = $reactor;
  }

  /**
   * {@inheritdoc}
   *
   * Note: no strict return type here to retain compatibility across Drupal versions.
   */
  public function get($channel) {
    $channelLogger = $this->innerFactory->get($channel);

    // Only wrap the samlauth channel.
    if ($channel === 'samlauth') {
      return new SamlauthChannelWrapper($channelLogger, $this->reactor);
    }

    return $channelLogger;
  }

  /**
   * {@inheritdoc}
   *
   * Must match signature exactly (include $priority and no return type).
   */
  public function addLogger(LoggerInterface $logger, $priority = 0) {
    $this->innerFactory->addLogger($logger, $priority);
  }

}
