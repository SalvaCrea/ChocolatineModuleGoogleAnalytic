<?php
namespace Chocolatine\Modules\GoogleApi\Model;

class self_model extends Chocolatine\Pattern\Module\Model
{

  var $type = 'self';

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
