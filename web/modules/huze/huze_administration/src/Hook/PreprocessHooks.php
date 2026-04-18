<?php

declare(strict_types=1);

namespace Drupal\huze_administration\Hook;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Routing\AdminContext;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PreprocessHooks implements ContainerInjectionInterface
{
  /**
   * Constructs a new PreprocessHooks object.
   *
   * @param \Drupal\Core\Routing\AdminContext $admin_context
   *   The admin context service.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   */
  public function __construct(
    private AdminContext $adminContext,
    private RequestStack $requestStack,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('router.admin_context'),
      $container->get('request_stack'),
    );
  }

  /**
   * Implements preprocess_page().
   */
  #[Hook('preprocess_page')]
  public function preprocessPage(&$variables): void
  {
    if ($this->isAdminPagesRequest()) {
      $this->attachAdminPagesLibrary($variables);
    }
  }

  /**
   * Implements hook_form_alter().
   */
  #[Hook('form_alter')]
  public function formAlter(array &$form, FormStateInterface $form_state, string $form_id): void
  {
    if ($this->isAdminPagesRequest()) {
      $this->attachAdminPagesLibrary($form);
    }
  }

  /**
   * Implements hook_preprocess_navigation().
   */
  #[Hook('preprocess_navigation')]
  public function preprocessNavigation(&$variables): void
  {
    $variables['#attached']['library'][] = 'huze_administration/navigation';
  }

  /**
   * Attach admin library without duplicates.
   */
  private function attachAdminPagesLibrary(array &$build): void
  {
    $build['#attached']['library'] ??= [];
    if (!in_array('huze_administration/admin_pages', $build['#attached']['library'], TRUE)) {
      $build['#attached']['library'][] = 'huze_administration/admin_pages';
    }
  }

  /**
   * Determines if current request belongs to admin pages flow.
   */
  private function isAdminPagesRequest(): bool
  {
    if ($this->adminContext->isAdminRoute()) {
      return TRUE;
    }

    $request = $this->requestStack->getCurrentRequest();
    if (!$request) {
      return FALSE;
    }

    $destination = $request->query->get('destination');
    if (is_string($destination) && str_starts_with('/' . ltrim($destination, '/'), '/admin/')) {
      return TRUE;
    }

    $referer = $request->headers->get('referer');
    if (is_string($referer)) {
      $referer_path = parse_url($referer, PHP_URL_PATH);
      if (is_string($referer_path) && str_starts_with($referer_path, '/admin/')) {
        return TRUE;
      }
    }

    return FALSE;
  }
}
