<?php

/**
 * @file
 * Cockpit backup admin functions.
 */

// Module ACL definitions.
$this("acl")->addResource('Backup', [
  'manage.view',
  'manage.create',
  'manage.restore',
  'manage.delete',
]);

$app->on('admin.init', function () use ($app) {

  // Bind admin routes /backup.
  $this->bindClass('Backup\\Controller\\Admin', 'backup');

  if ($app->module('cockpit')->hasaccess('Backup', 'manage.view')) {
    // Add to modules menu.
    $this('admin')->addMenuItem('modules', [
      'label' => 'Backup',
      'icon'  => 'assets:app/media/icons/database.svg',
      'route' => '/backup',
      'active' => strpos($this['route'], '/backup') === 0,
    ]);
  }

});
