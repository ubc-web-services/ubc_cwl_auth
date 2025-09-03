<?php

namespace Drupal\ubc_cwl_auth\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;

class AccessDeniedController extends ControllerBase {

  /**
   * Redirects the user to another page.
   */
  public function redirectToCwlLogin(): RedirectResponse {

    //TODO - Add return argument?

    return new RedirectResponse('/saml/login');
  }

}
