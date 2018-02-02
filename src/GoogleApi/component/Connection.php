<?php
namespace Chocolatine\Modules\GoogleApi\component;

class Connection extends \Chocolatine\Pattern\Module\Component
{
  /**
   * The path file for credentials google
   * @var string
   */
  private $key_file_location = __DIR__ . '/../service-account-credentials.json';
  /**
   * Name of application
   * @var string
   */
  public $application_name;
  /**
   * The id client
   * @var string
   */
  private $id_client;
  /**
   * The secret key of loging
   * @var [type]
   */
  private $secret_key;
  /**
   * The key dev for loging
   * @var  string
   */
  private $developer_key;
  /**
   * scope need by google
   * @var string
   */
  public $scopes ="https://www.googleapis.com/auth/analytics.readonly";
  /**
   * The url for redirection
   * @var string
   */
  public $redirect_uri;
  /**
   * Client google
   * @var object
   */
  public $client_google;
  /**
   * The token for connect google
   * @var string
   */
  public $google_token;
  public function __construct(){

          $google_configuration = \Chocolatine\get_configuration( 'google_keys' );
          $this->application_name = $google_configuration["application_name"];
          $this->id_client = $google_configuration["id_client"];
          $this->secret_key = $google_configuration["secret_key"];
          $this->developer_key = $google_configuration["developer_key"];
          $this->redirect_uri = "http://".$_SERVER["HTTP_HOST"] . $google_configuration["redirect_uri"];

          session_start();

          if ( !empty( $_SESSION['token_google'] ) ) {
            $this->google_token = $_SESSION['token_google'];
          }

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
