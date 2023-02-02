<?php

namespace Drupal\download_files\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

    $form['pass_phrase'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#description' => $this->t('Enter your email address to retrieve the file.'),
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
    parent::validateForm($form, $form_state);
    $pass_phrase = $form_state->getValue('pass_phrase');
    if (!strpos($pass_phrase, 'evolvingweb.com')) {
      $form_state->setErrorByName('pass_phrase', $this->t('Invalid email.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $uri = $form_state->getValue('file');
    $response = new BinaryFileResponse($uri);
    $response->setContentDisposition('attachment');
    $form_state->setResponse($response);
  }

}
