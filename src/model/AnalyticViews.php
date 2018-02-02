<?php
namespace Chocolatine\Modules\GoogleApi\Model;

class AnalyticViews extends \Chocolatine\Models\Simple
{

  public function model()
  {
      return array(
          "idView"=>  [
            "title"=> "The id google view",
            "type"=> "int"
          ],
          "PropertyId"=>  [
            "title"=> "The name of view",
            "type"=> "string",
          ],
          "accountId"=>  [
            "title"=> "The name of view",
            "type"=> "string",
          ],
          "name"=>  [
            "title"=> "The name of view",
            "type"=> "string",
          ],
          "type"=>  [
            "title"=> "type",
            "type"=> "string",
          ],
          "defaultPage"=>  [
            "title"=> "defaultPage",
            "type"=> "string",
          ],
          "created"=>  [
            "title"=> "created",
            "type"=> "string",
          ],
          "currency"=>  [
            "title"=> "currency",
            "type"=> "string",
          ],
          "timezone"=>  [
            "title"=> "timezone",
            "type"=> "string",
          ],
          "ecommerceTracking"=>  [
            "title"=> "ecommerceTracking",
            "type"=> "string",
          ],
          "enhancedeCommerce"=>  [
            "title"=> "enhancedeCommerce",
            "type"=> "string",
          ],
          "exclude"=>  [
            "title"=> "exclude",
            "type"=> "string",
          ]
      );
  }
}
