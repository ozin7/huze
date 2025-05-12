<?php

declare(strict_types=1);

namespace Drupal\huze_administration\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Form\FormStateInterface;


class FormAlterHooks
{
  /**
   * Implements hook_form_alter().
   */
  #[Hook('form_node_form_alter')]
  public function nodeFormAlter(&$form, FormStateInterface $form_state, $form_id): void
  {
    $form['status']['#group'] = 'meta';
    $form['options']['#access'] = false;
    $form['author']['#access'] = false;
    if (isset($form['path']['widget'][0])) {
      $form['path']['widget'][0]['#open'] = false;
    }
  }

  #[Hook('field_widget_single_element_form_alter')]
  public function widgetFormAlter(&$element, FormStateInterface $form_state, $context): void
  {
    $element['#after_build'][] = self::class . '::removeTextareaHelp';
  }

  public static function removeTextareaHelp($form_element, FormStateInterface $form_state): array
  {
    if (isset($form_element['format'])) {
      unset($form_element['format']['help']);
      unset($form_element['format']['guidelines']);

      // If nothing is left in the wrapper, hide it as well.
      if (isset($form_element['#allowed_formats'])) {
        unset($form_element['format']['#type']);
        unset($form_element['format']['#theme_wrappers']);
      }
    }
    return $form_element;
  }

}
