<?php
namespace Chocolatine\Modules\GoogleApi\component;

class Connection extends \Chocolatine\Pattern\Module\Component
{
  /**
   * The path file for credentials google
   * @var string
   */
  private $key_file_location;
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
  public function get_key_location(){
     return \Chocolatine\get_folder() . '/themes/Custom/others/service-account-credentials.json';
  }
  public function get_client(){
          return $this->client_google;
  }
  public function create_client_google(){


      $client = new \Google_Client();
      $client->setAccessType('offline');
      $client->setApplicationName( $this->application_name );
      $client->setClientId( $this->id_client );
      $client->setClientSecret( $this->secret_key );
      $client->setDeveloperKey( $this->developer_key );
      // $client->setAuthConfig( $this->get_key_location() );
      $client->setScopes( $this->scopes );
      $client->setRedirectUri( $this->redirect_uri );
      $this->client_google = $client;

  }
  public function connection()
  {

        $this->create_client_google();

        if (isset($_GET['code'])) {
            $this->setToken();
        }

        if ( empty( $this->google_token ) ) {
          $authUrl = $this->client_google->createAuthUrl();
          header("Location: ".$authUrl);
          die;
        }else{
            $this->client_google->setAccessToken( $this->google_token );
        }

        return $this->get_client();

  }
  public function setToken(){

        $this->client_google->authenticate($_GET['code']);
        $_SESSION['token_google'] = $this->client_google->getAccessToken();
        header("Location: /");
        die;

  }


}
