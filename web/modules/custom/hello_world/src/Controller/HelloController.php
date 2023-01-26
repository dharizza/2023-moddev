<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
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

    return [
      '#type' => 'markup',
      '#markup' => $output,
    ];
  }

  /**
   * Prints person name and link to node.
   */
  public function helloNameNode($name, $nid) {
    // $node = Node::load($nid);
    // Print node.
    // ksm($node);

    // Alternatives to printing node titles.
    // ksm($node->getTitle());
    // ksm($node->title->value);

    // General pattern to printing field values.
    // ksm($node->body->value);

    // Get the link to a node.
    // $link = $node->toLink();

    // Build link manually using Url and Link classes.
    // $url = Url::fromRoute('entity.node.canonical', ['node' => $nid]);
    // $link = Link::fromTextAndUrl($node->getTitle(), $url);

    $output = [];
    if (is_numeric($nid)) {
      $node = Node::load($nid);
      if ($node) {
        $title = $node->toLink()->toString();
        $output = $this->t('Hello @name! The title of the node is @title', ['@name' => $name, '@title' => $title]);
      }
      else {
        $output = $this->t('Hey :name! That nid does not exists!', [':name' => $name]);
      }
    }
    else {
      $output = $this->t('Hey :name! That nid should be a number!', [':name' => $name]);
    }

    return [
      '#type' => 'markup',
      '#markup' => $output,
    ];
  }

}
