<?php

namespace Drupal\download_files\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a download files block block.
 *
 * @Block(
 *   id = "download_files_block",
 *   admin_label = @Translation("Download files block"),
 *   category = @Translation("Custom")
 * )
 */
class DownloadFilesBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'foo' => $this->t('Hello world!'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['foo'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Foo'),
      '#default_value' => $this->configuration['foo'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['foo'] = $form_state->getValue('foo');
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'download documents');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('\Drupal\download_files\Form\DownloadFilesForm');
    $form['#title'] = $this->t("Get your files here!");
    return $form;
  }

}
