<?php

/**
 * @file
 * Contains \Drupal\Tests\workbench_moderation\Kernel\EntityStateChangeValidationTest.
 */

namespace Drupal\Tests\workbench_moderation\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;

/**
 * @coversDefaultClass \Drupal\workbench_moderation\Plugin\Validation\Constraint\ModerationStateValidator
 * @group moderation_state
 */
class EntityStateChangeValidationTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['node', 'moderation_state', 'user', 'system'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installEntitySchema('node');
    $this->installEntitySchema('user');
    $this->installConfig('moderation_state');
  }

  /**
   * Test valid transitions.
   *
   * @covers ::validate
   */
  public function testValidTransition() {
    $node_type = NodeType::create([
      'type' => 'example',
    ]);
    $node_type->save();
    $node = Node::create([
      'type' => 'example',
      'title' => 'Test title',
      'moderation_state' => 'draft',
    ]);
    $node->save();

    $node->moderation_state->target_id = 'needs_review';
    $this->assertCount(0, $node->validate());
  }

  /**
   * Test invalid transitions.
   *
   * @covers ::validate
   */
  public function testInvalidTransition() {
    $node_type = NodeType::create([
      'type' => 'example',
    ]);
    $node_type->save();
    $node = Node::create([
      'type' => 'example',
      'title' => 'Test title',
      'moderation_state' => 'draft',
    ]);
    $node->save();

    $node->moderation_state->target_id = 'published';
    $violations = $node->validate();
    $this->assertCount(1, $violations);

    $this->assertEquals('Invalid state transition from <em class="placeholder">Draft</em> to <em class="placeholder">Published</em>', $violations->get(0)->getMessage());
  }

}