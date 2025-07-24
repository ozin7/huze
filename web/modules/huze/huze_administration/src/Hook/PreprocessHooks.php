<?php

declare(strict_types=1);

namespace Drupal\huze_administration\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Routing\AdminContext;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;

class PreprocessHooks implements ContainerInjectionInterface
{
  /**
   * Constructs a new PreprocessHooks object.
   *
   * @param \Drupal\Core\Routing\AdminContext $admin_context
   *   The admin context service.
   */
  public function __construct(private AdminContext $adminContext) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('router.admin_context')
    );
  }

  /**
   * Implements preprocess_page().
   */
  #[Hook('preprocess_page')]
  public function preprocessPage(&$variables): void
  {
    if ($this->adminContext->isAdminRoute()) {
      // Add the admin pages library to the page.
      $variables['#attached']['library'][] = 'huze_administration/admin_pages';
    }
  }
  /**
   * Implements preprocess_page().
   */
  #[Hook('preprocess_navigation')]
  public function preprocessNavigation(&$variables): void
  {
    $variables['#attached']['library'][] = 'huze_administration/navigation';
  }
}
