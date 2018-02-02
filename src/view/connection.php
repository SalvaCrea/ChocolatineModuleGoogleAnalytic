<?php
namespace Chocolatine\Modules\GoogleApi\view;


class connection extends \Chocolatine\Pattern\Module\View
{
  public function main()
  {

        $module = \Chocolatine\get_module( 'GoogleApi' );
        $module->component->Connection->connection();

  }
}
