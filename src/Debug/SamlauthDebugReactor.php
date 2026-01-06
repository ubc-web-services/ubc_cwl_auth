<?php

namespace Drupal\ubc_cwl_auth\Debug;

use Drupal\Core\Config\ConfigFactoryInterface;
use DOMDocument;
use DOMXPath;

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

    $config = $this->configFactory->get('ubc_cwl_auth.settings');
    if($config->get('ubc_cwl_auth_debug') == 1) {

      if (strpos($message, 'ACS received SAML response') !== FALSE || TRUE) {

        $attributes = $this->extractAttributes($context['@message'], $config);
        $data = json_encode($attributes);

        $cid = time();
        \Drupal::cache('ubc_cwl_auth')->set($cid, $data, (time() + 24*60*60));
      }
    }
  }

  private function extractAttributes($xml, $config) {

    // Load XML into DOM
    $doc = new DOMDocument();
    $doc->loadXML($xml);

    // Create XPath with namespaces
    $xpath = new DOMXPath($doc);
    $xpath->registerNamespace('saml2', 'urn:oasis:names:tc:SAML:2.0:assertion');

    // The attributes we care about
    $targetAttrs = [$config->get('ubc_cwl_auth_attr1'),
                    $config->get('ubc_cwl_auth_attr2'),
                    $config->get('ubc_cwl_auth_attr3'),
                    $config->get('ubc_cwl_auth_attr4'),
                    $config->get('ubc_cwl_auth_attr5')];
    $targetAttrs = array_filter($targetAttrs);

    $results = [];

    // Loop through each target attribute
    foreach ($targetAttrs as $attrName) {
        $query = "//saml2:Attribute[@FriendlyName='$attrName']/saml2:AttributeValue";
        $values = [];
        foreach ($xpath->query($query) as $node) {
            $values[] = trim($node->textContent);
        }
        // If multiple values (like eduPersonAffiliation), join with comma
        if ($values) {
            $results[$attrName] = implode(', ', $values);
        }
    }

    // Print the results
    $return = [];
    foreach ($results as $key => $value) {
        $return[$key] = $value;
    }
    return $return;
  }

}
