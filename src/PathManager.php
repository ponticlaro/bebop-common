<?php

namespace Ponticlaro\Bebop\Common;

use Ponticlaro\Bebop\Common\Patterns\Traits\Singleton;
use Ponticlaro\Bebop\Common\Patterns\Traits\CollectionRelativePathMutator;

class PathManager extends Collection {

  use Singleton;
  use CollectionRelativePathMutator;

  /**
   * {@inheritDoc}
   */
  public function __construct( array $data = [] )
  {
    parent::__construct( $data );

    $uploads_data = wp_upload_dir();
    $template_dir = get_template_directory();

    // Add default paths
    $this->set( 'root', ABSPATH );
    $this->set( 'admin', '' );
    $this->set( 'plugins', '' );
    $this->set( 'content', '' );
    $this->set( 'uploads', $uploads_data['basedir'] );
    $this->set( 'themes', str_replace( '/'. basename( $template_dir ), '', $template_dir ) );
    $this->set( 'theme', get_template_directory());
  }
}