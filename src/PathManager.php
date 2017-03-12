<?php
/**
 * PathManager class.
 *
 * @package Bebop\Common
 * @since 1.0.0
 */

namespace Ponticlaro\Bebop\Common;

use Ponticlaro\Bebop\Common\Patterns\Traits\Singleton;
use Ponticlaro\Bebop\Common\Patterns\Traits\CollectionPathMutator;

/**
 * Collection of paths available sitewide
 *
 * @package Bebop\Common
 * @since 1.0.0
 * @since 1.1.5 Extends from Collection; Uses Singleton and CollectionPathMutator traits
 * @api
 * @see \Ponticlaro\Bebop\Common\Collection Extended collection class
 */
class PathManager extends Collection {

  use Singleton;
  use CollectionPathMutator;

  /**
   * {@inheritDoc}
   */
  public function __construct( array $data = [] )
  {
    $this->disableDottedNotation();

    parent::__construct( $data );

    $uploads_data = wp_upload_dir();
    $template_dir = get_template_directory();

    // Set default paths
    $this->set( 'root', ABSPATH );
    $this->set( 'admin', '' );
    $this->set( 'plugins', '' );
    $this->set( 'content', '' );
    $this->set( 'uploads', $uploads_data['basedir'] );
    $this->set( 'themes', str_replace( '/'. basename( $template_dir ), '', $template_dir ) );
    $this->set( 'theme', get_template_directory());
  }
}