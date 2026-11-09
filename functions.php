<?php
/**
 * Theme bootstrap.
 *
 * @package Obel
 */

namespace Obel;

require_once __DIR__ . '/class-theme.php';
( new Theme() )->register();

require_once __DIR__ . '/class-images.php';
( new Images() )->register();
