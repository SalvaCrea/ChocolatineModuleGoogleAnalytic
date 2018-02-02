<?php
namespace Chocolatine\Modules\GoogleApi\view;


class main extends \Chocolatine\Pattern\Module\view
{
  public function connect( $requete,  $response, $args )
  {
        $module = \Chocolatine\get_module( 'GoogleApi' );
        $module->component->Connection->setToken();
  }
}
