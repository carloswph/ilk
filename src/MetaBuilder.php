<?php

namespace Ilk;

/**
 * Manages custom post type metaboxes
 *
 * @since 1.1.0
 * @package Ilk\MetaBuilder
 * @author WP Helpers | Carlos Matos
 */
class MetaBuilder
{

	public $post;

	public function __construct(Builder $post)
	{

		$this->post = $post->type;

	}

	public function add($name) {}

	public function drop($name) {}

}