<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * Class HelloController.
 *
 * Provides methods for responding to different routes.
 */
class HelloController extends ControllerBase {

  /**
   * Hello world.
   */
  public function hello($name = NULL) {
    if ($name) {
      $output = $this->t("Hello @person", ['@person' => $name]);
    }
    else {
      $output = $this->t("Hello world!");
    }

    $node = Node::load(1);
    ksm($node->getTitle());

    return [
      '#type' => 'markup',
      '#markup' => $output,
    ];
  }

}
