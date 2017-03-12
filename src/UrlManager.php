<?php

namespace Ponticlaro\Bebop\Common;

use Ponticlaro\Bebop\Common\Patterns\Traits\Singleton;
use Ponticlaro\Bebop\Common\Patterns\Traits\CollectionRelativePathMutator;

class UrlManager extends Collection {

  use Singleton;
  use CollectionRelativePathMutator;

  /**
   * {@inheritDoc}
   */
  public function __construct( array $data = [] )
  {
    parent::__construct( $data );

    $uploads_data = wp_upload_dir();
    $template_url = get_bloginfo( 'template_url' );

    // Instantiate paths collection object
    $this->set( 'home', home_url() );
    $this->set( 'admin', admin_url() );
    $this->set( 'plugins', plugins_url() );
    $this->set( 'content', content_url() );
    $this->set( 'uploads', $uploads_data['baseurl'] );
    $this->set( 'themes', str_replace( '/'. basename( $template_url ), '', $template_url ) );
    $this->set( 'theme', $template_url );
  }
}