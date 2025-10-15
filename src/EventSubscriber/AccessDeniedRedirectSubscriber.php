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
use Drupal\Core\Url;

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
    $request = $event->getRequest();
    $source_page = $request->getRequestUri();

    $redirect_url = Url::fromUri('internal:/saml/login', [
      'query' => ['destination' => $source_page],
    ])->toString();

    $response = new RedirectResponse($redirect_url);
    $event->setResponse($response);
  }

}
