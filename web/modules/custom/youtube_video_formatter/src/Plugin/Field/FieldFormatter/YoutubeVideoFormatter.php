<?php

namespace Drupal\youtube_video_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'Youtube Video' formatter.
 *
 * @FieldFormatter(
 *   id = "youtube_video_formatter",
 *   label = @Translation("Youtube Video"),
 *   field_types = {
 *     "string"
 *   }
 * )
 */
class YoutubeVideoFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'width' => '',
      'height' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {

    $form['width'] = [
      '#type' => 'number',
      '#title' => 'Video Width',
      '#default_value' => $this->getSetting('width'),
      '#required' => TRUE,
    ];

    $form['height'] = [
      '#type' => 'number',
      '#title' => 'Video Height',
      '#default_value' => $this->getSetting('height'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary[] = 'The width is ' . $this->getSetting('width');
    $summary[] = 'The height is ' . $this->getSetting('height');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#theme' => 'youtube_video',
        '#youtube_id' => $item->value,
        '#width' => $this->getSetting('width'),
        '#height' => $this->getSetting('height'),
      ];
    }

    return $elements;
  }

}
