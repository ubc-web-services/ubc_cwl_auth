<?php

namespace Drupal\ubc_cwl_auth\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;

class AccessDeniedRedirectSubscriber implements EventSubscriberInterface {

  protected UrlGeneratorInterface $urlGenerator;
  protected AccountInterface $currentUser;

  public function __construct(UrlGeneratorInterface $url_generator, AccountInterface $current_user) {
    $this->urlGenerator = $url_generator;
    $this->currentUser = $current_user;
  }

  public static function getSubscribedEvents(): array {
    return [
      KernelEvents::EXCEPTION => ['onKernelException', 0],
    ];
  }

  public function onKernelException(ExceptionEvent $event): void {

    $exception = $event->getThrowable();

    // Only handle 403 exceptions.
    if (!$exception instanceof AccessDeniedHttpException) {
      return;
    }

    // If the user has the 'CWL' role, let the default 403 page load.
    if (in_array('CWL', $this->currentUser->getRoles(), TRUE)) {
      //\Drupal::logger('UBC CWL')->debug('has CWL');
      return;
    }

    // Redirect other users to CWL Login
    //\Drupal::logger('UBC CWL')->debug('No CWL role... redirect');
    $url = $this->urlGenerator->generate('ubc_cwl_auth.ubc_cwl_redirect');
    $response = new RedirectResponse($url);
    $event->setResponse($response);
  }

}
