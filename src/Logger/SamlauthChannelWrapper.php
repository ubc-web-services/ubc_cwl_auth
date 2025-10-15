<?php

namespace Drupal\ubc_cwl_auth\Logger;

use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\ubc_cwl_auth\Debug\SamlauthDebugReactor;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Session\AccountInterface;

/**
 * Wraps the samlauth logger channel so we can react to debug() messages.
 */
class SamlauthChannelWrapper implements LoggerChannelInterface {

  protected LoggerChannelInterface $channel;
  protected SamlauthDebugReactor $reactor;

  public function __construct(LoggerChannelInterface $channel, SamlauthDebugReactor $reactor) {
    $this->channel = $channel;
    $this->reactor = $reactor;
  }

  // --- PSR-3 methods ---
  public function emergency(\Stringable|string $message, array $context = []): void { $this->log(LogLevel::EMERGENCY, $message, $context); }
  public function alert(\Stringable|string $message, array $context = []): void { $this->log(LogLevel::ALERT, $message, $context); }
  public function critical(\Stringable|string $message, array $context = []): void { $this->log(LogLevel::CRITICAL, $message, $context); }
  public function error(\Stringable|string $message, array $context = []): void { $this->log(LogLevel::ERROR, $message, $context); }
  public function warning(\Stringable|string $message, array $context = []): void { $this->log(LogLevel::WARNING, $message, $context); }
  public function notice(\Stringable|string $message, array $context = []): void { $this->log(LogLevel::NOTICE, $message, $context); }
  public function info(\Stringable|string $message, array $context = []): void { $this->log(LogLevel::INFO, $message, $context); }
  public function debug(\Stringable|string $message, array $context = []): void { $this->log(LogLevel::DEBUG, $message, $context); }
  public function log($level, \Stringable|string $message, array $context = []): void {
    if ($level === LogLevel::DEBUG) {
      try {
        $this->reactor->handleDebugMessage((string) $message, $context);
      }
      catch (\Throwable $e) {
        $this->channel->error('ubc_cwl_auth reactor error: @msg', ['@msg' => $e->getMessage()]);
      }
    }
    $this->channel->log($level, $message, $context);
  }

  // --- Drupal-specific methods ---
  public function setRequestStack(?RequestStack $requestStack = null): void {
    if (method_exists($this->channel, 'setRequestStack')) {
      $this->channel->setRequestStack($requestStack);
    }
  }

  public function setCurrentUser(?AccountInterface $account = null): void {
    if (method_exists($this->channel, 'setCurrentUser')) {
      $this->channel->setCurrentUser($account);
    }
  }

  public function setLoggers(array $loggers = []): void {
    if (method_exists($this->channel, 'setLoggers')) {
      $this->channel->setLoggers($loggers);
    }
  }

  public function addLogger(LoggerInterface $logger, $priority = 0): void {
    if (method_exists($this->channel, 'addLogger')) {
      $this->channel->addLogger($logger, $priority);
    }
  }

  public function getName(): string {
    if (method_exists($this->channel, 'getName')) {
      return $this->channel->getName();
    }
    return '';
  }
}
