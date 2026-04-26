<?php

declare(strict_types=1);

namespace Drupal\huze_administration\Hook;

use Drupal\Core\Hook\Attribute\Hook;

class LinksHooks
{
  #[Hook('menu_links_discovered_alter')]
  public function menuLinksDiscoveredAlter(array &$links): void {
    if (isset($links['paragraphs_library.paragraphs_library_item.collection'])) {
      $links['paragraphs_library.paragraphs_library_item.collection']['title'] = t('Elements Library');
    }
  }

  #[Hook('local_tasks_alter')]
  public function localTasksAlter(array &$local_tasks): void {
    if (isset($local_tasks['entity.paragraphs_library_item.collection'])) {
      $local_tasks['entity.paragraphs_library_item.collection']['title'] = t('Elements Library');
    }
  }

  #[Hook('menu_local_actions_alter')]
  public function menuLocalActionsAlter(array &$local_actions): void {
    if (isset($local_actions['entity.paragraphs_library_item.add_form'])) {
      $local_actions['entity.paragraphs_library_item.add_form']['title'] = t('Add Library Element');
    }
  }
}
