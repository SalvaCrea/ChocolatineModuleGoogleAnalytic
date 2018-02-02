<?php
namespace Chocolatine\Modules\GoogleApi\component;

class AnalyticsTools extends \Chocolatine\Pattern\Module\Component
{
  public function __construct(){

  }
  public function connection(){

    $module = \Chocolatine\get_module( 'GoogleApi' );
    $this->client_google = $module->component->Connection->connection();
    
    return $this->ServiceAnalytics = new \Google_Service_Analytics( $this->client_google );

  }
  /**
   * Function list Accounts
   * @return object Data Informations Account
   */
  public function listManagementAccounts(){

        $accounts = $this->ServiceAnalytics->management_accounts->listManagementAccounts();

        $data = [
            "itemsPerPage" => $accounts->itemsPerPage,
            "startIndex"   => $accounts->startIndex,
            "totalResults" => $accounts->totalResults,
            "items"         => array()
        ];

        foreach ($accounts->getItems() as $account) {

            $model = \Chocolatine\get_model( 'GoogleApi@AnalyticAccounts', true );

            $item = [
              "idAccount" => $account->getId(),
              "name"      => $account->getName(),
              "created"   => $account->getCreated(),
              "updated"   => $account->getUpdated()
            ];

            $model->set_data( $item );

            $data['items'] []= $model;
        }

        return $data;
  }
  /**
   * List all  List Properties
   * @param  string $idAccount The id of account
   * @return array            data of properties google
   */
  public function listProperties( $idAccount = '~all' ){

    $properties = $this->ServiceAnalytics->management_webproperties
      ->listManagementWebproperties( $idAccount );

      $data = [
          "itemsPerPage" => $properties->itemsPerPage,
          "startIndex"   => $properties->startIndex,
          "totalResults" => $properties->totalResults,
          "items"         => array()
      ];

      foreach ( $properties->getItems() as $property ) {

          $model = \Chocolatine\get_model( 'GoogleApi@AnalyticProperties', true );

          $item = [
            "idProperty" => $property->getId(),
            "accountId"  => $property->getAccountId(),
            "name"       => $property->getName(),
            "url"        => $property->getWebsiteUrl(),
            "created"    => $property->getCreated(),
            "updated"    => $property->getUpdated()
          ];

          $model->set_data( $item );

          $data['items'] []= $model;
      }

      return $data;
  }
/**
 * List Views Google Analytics
 * @param  string $accountId     The Account Id
 * @param  string $webPropertyId Web property ID for the views (profiles) to retrieve
 * @return array                data of properties View
 */
  public function listView( $accountId ,$webPropertyId = '~all' ){

    $views = $this->ServiceAnalytics->management_profiles
      ->listManagementProfiles( $accountId, $webPropertyId );

      $data = [
          "itemsPerPage" => $views->itemsPerPage,
          "startIndex"   => $views->startIndex,
          "totalResults" => $views->totalResults,
          "items"         => array()
      ];

      foreach ( $views->getItems() as $view ) {

          $item                 = [
            "idView"            => $view->getId(),
            "PropertyId"        => $view->getWebPropertyId(),
            "accountId"         => $view->getAccountId(),
            "name"              => $view->getName(),
            "type"              => $view->getType(),
            "defaultPage"       => $view->getDefaultPage(),
            "created"           => $view->getCreated(),
            "exclude"           => $view->getExcludeQueryParameters(),
            "currency"          => $view->getCurrency(),
            "timezone"          => $view->getTimezone(),
            "ecommerceTracking" => $view->getECommerceTracking(),
            "enhancedeCommerce" => $view->getEnhancedECommerceTracking(),
            "exclude"           => $view->getExcludeQueryParameters()
          ];

          $model = \Chocolatine\get_model( 'GoogleApi@AnalyticViews', true );
          $model->set_data( $item );

          $data['items'] []= $model;
      }

    return $data;
  }
}
