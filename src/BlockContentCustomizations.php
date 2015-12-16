<?php
/**
 * @file
 * Contains Drupal\moderation_state\BlockContentCustomizations.
 */

namespace Drupal\moderation_state;

use Drupal\block_content\Entity\BlockContentType;
use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Form\FormStateInterface;


/**
 * Customizations for block content entities.
 */
class BlockContentCustomizations extends GenericCustomizations {

  /**
   * {@inheritdoc}
   */
  function getEntityTypeId() {
    return 'block_content';
  }

  /**
   * {@inheritdoc}
   */
  function getEntityBundleClass() {
    return BlockContentType::class;
  }

  /**
   * {@inheritdoc}
   */
  public function onEntityModerationFormSubmit(ConfigEntityInterface $bundle) {
    /** @var BlockContentType $bundle */
    $bundle->set('revision', TRUE);
    $bundle->save();
  }

  /**
   * {@inheritdoc}
   */
  public function enforceRevisionsEntityFormAlter(array &$form, FormStateInterface $form_state, $form_id) {
    $form['revision_information']['revision']['#default_value'] = TRUE;
    $form['revision_information']['revision']['#disabled'] = TRUE;
    $form['revision_information']['revision']['#description'] = $this->t('Revisions must be required when moderation is enabled.');
  }

  /**
   * {@inheritdoc}
   */
  public function enforceRevisionsBundleFormAlter(array &$form, FormStateInterface $form_state, $form_id) {
    $form['revision']['#default_value'] = 1;
    $form['revision']['#disabled'] = TRUE;
    $form['revision']['#description'] = $this->t('Revisions must be required when moderation is enabled.');
  }

}
