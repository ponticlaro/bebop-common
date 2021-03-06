<?php

namespace Ponticlaro\Bebop\Common;

use Ponticlaro\Bebop\Common\Patterns\SingletonTrait;
use Ponticlaro\Bebop\Common\Patterns\CollectionPathMutatorTrait;

/**
 * Collection of URLs available sitewide
 *
 * @package Bebop\Common
 * @since 1.0.0
 * @since 1.1.5 Extends from Collection; Uses SingletonTrait and CollectionPathMutatorTrait traits
 * @api
 * @see \Ponticlaro\Bebop\Common\Collection Extended collection class
 */
class UrlManager extends Collection {

  use SingletonTrait;
  use CollectionPathMutatorTrait;

  /**
   * {@inheritDoc}
   */
  public function __construct( array $data = [] )
  {
    $this->disableDottedNotation();

    parent::__construct( $data );

    $uploads_data = wp_upload_dir();
    $template_url = get_bloginfo( 'template_url' );

    // Set default URLs
    $this->set( 'home', home_url() );
    $this->set( 'admin', admin_url() );
    $this->set( 'plugins', plugins_url() );
    $this->set( 'content', content_url() );
    $this->set( 'uploads', $uploads_data['baseurl'] );
    $this->set( 'themes', str_replace( '/'. basename( $template_url ), '', $template_url ) );
    $this->set( 'theme', $template_url );
  }
}