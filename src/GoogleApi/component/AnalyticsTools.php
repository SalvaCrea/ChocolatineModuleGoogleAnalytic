<?php
namespace Chocolatine\Modules\GoogleApi\component;

class AnalyticsTools extends \Chocolatine\Pattern\Module\Component
{

  /**
   * Google analytics instanced
   * @var object
   */
  public $analytic_google;

  public function __construct(){
          $this->analyticConnect();
  }
  public function analyticConnect(){

    $module = \Chocolatine\get_module( 'GoogleApi' );
    $module->component->Connection->setToken();
    $this->analytic_google = new \Google_Service_Analytics( $module->component->Connection->client_google );

  }
  public function create_client_google(){



      $key = file_get_contents($this->key_file_location);
      $client = new \Google_Client();
      $client->setAccessType('offline');
      $client->setApplicationName( $this->application_name );
      $client->setClientId( $this->id_client );
      $client->setClientSecret( $this->secret_key );
      $client->setDeveloperKey( $this->developer_key );
      $client->setAuthConfig($this->key_file_location);
      $client->setScopes( $this->scopes );
      $client->setRedirectUri( $this->redirect_uri );
      $this->client_google = $client;

  }
  public function connection()
  {
        $this->create_client_google();

        if ( empty( $this->google_token ) ) {
          $authUrl = $this->client_google->createAuthUrl();
          header("Location: ".$authUrl);
          die;
        }else{
            $this->client_google->setAccessToken( $this->google_token );
        }

        $this->analytics = new \Google_Service_Analytics( $this->client_google );

  }
  public function setToken(){

    if (isset($_GET['code'])) { // we received the positive auth callback, get the token and store it in session
        $this->create_client_google();
        $this->client_google->authenticate($_GET['code']);
        $this->google_token = $this->client_google->getAccessToken();
        header("Location: /home");
        die;
    }
  }
  function test( $analytics ){

    // Replace with your view ID, for example XXXX.
    $VIEW_ID = "<REPLACE_WITH_VIEW_ID>";

    // Create the DateRange object.
    $dateRange = new Google_Service_AnalyticsReporting_DateRange();
    $dateRange->setStartDate("7daysAgo");
    $dateRange->setEndDate("today");

    // Create the Metrics object.
    $sessions = new Google_Service_AnalyticsReporting_Metric();
    $sessions->setExpression("ga:sessions");
    $sessions->setAlias("sessions");

    // Create the ReportRequest object.
    $request = new Google_Service_AnalyticsReporting_ReportRequest();
    $request->setViewId($VIEW_ID);
    $request->setDateRanges($dateRange);
    $request->setMetrics(array($sessions));

    $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
    $body->setReportRequests( array( $request) );
    return $analytics->reports->batchGet( $body );

  \Chocolatine\dump( $items );
  }

}
