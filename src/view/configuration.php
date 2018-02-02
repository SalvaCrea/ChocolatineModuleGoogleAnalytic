<?php
namespace Chocolatine\Modules\GoogleApi\view;


class configuration extends \Chocolatine\Pattern\Module\View
{
  public function main()
  {
      $formService = \Chocolatine\get_service( 'form' );

      $model = \Chocolatine\get_service( 'GoogleApi@GoogleSettings' );

      $formService->generate_form( $model );
  }
}
