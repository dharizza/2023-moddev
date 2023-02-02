<?php

namespace Drupal\download_files\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Provides a Download files form.
 */
class DownloadFilesForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'download_files_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['file'] = [
      '#type' => 'select',
      '#title' => $this->t('Select a file'),
      '#required' => TRUE,
      '#options' => $this->getFiles(),
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Download'),
    ];

    return $form;
  }

  /**
   * Get array with list of files.
   */
  public function getFiles() {
    // Get files using database abstraction layer.
    $result = \Drupal::database()
      ->select('file_managed', 'f')
      ->fields('f', ['filename', 'uri'])
      ->condition('f.status', 1)
      ->execute()
      ->fetchAll();

    $files = [];
    foreach ($result as $file) {
      $files[$file->uri] = $file->filename;
    }

    // Get files using entity queries.
    // $fids = \Drupal::entityQuery('file')
    //   ->condition('status', 1)
    //   ->execute();
    // $files = File::loadMultiple($fids);
    // $options = [];
    // foreach ($files as $file) {
    //   $options[$file->getFileUri()] = $file->getFilename();
    // }
    return $files;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (mb_strlen($form_state->getValue('message')) < 10) {
      $form_state->setErrorByName('message', $this->t('Message should be at least 10 characters.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addStatus($this->t('The message has been sent.'));
    $form_state->setRedirect('<front>');
  }

}
