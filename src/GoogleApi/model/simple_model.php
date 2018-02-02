<?php
namespace Chocolatine\Modules\GoogleApi\Model;

class simple_model extends Chocolatine\Pattern\Module\Model
{

  var $type = 'simple';

  public function model()
  {
      return $model = array(
          "email"=>  [
            "title"=> "Email of user",
            "type"=> "string",
            "pattern" => "^\\S+@\\S+$",
          ],
          "name"=>  [
            "title"=> "the name of enterprise",
            "type"=> "string"
          ]
      );
  }
}
