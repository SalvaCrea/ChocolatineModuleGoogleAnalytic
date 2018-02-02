<?php

namespace Chocolatine\Modules\GoogleApi\component;

class Report extends \Chocolatine\Pattern\Module\Component
{
  public function __construct(){

  }
  public function connection(){


    $module = \Chocolatine\get_module( 'GoogleApi' );
    $this->client_google = $module->component->Connection->connection();

    return $this->analyticsReporting = new \Google_Service_AnalyticsReporting( $this->client_google );

  }
  public function getReport( $id_views ) {

    // Create the DateRange object.
    $dateRange = new \Google_Service_AnalyticsReporting_DateRange();
    $dateRange->setStartDate("36daysAgo");
    $dateRange->setEndDate("today");



    // Create the Metrics object.
    $sessions = new \Google_Service_AnalyticsReporting_Metric();
    $sessions->setExpression("ga:pageviews");

    $sessions->setAlias("Mon pageur");

    //Create the Dimensions object.
    $browser = new \Google_Service_AnalyticsReporting_Dimension();
    $browser->setName("ga:pagePath");

    // Create the ReportRequest object.
    $request = new \Google_Service_AnalyticsReporting_ReportRequest();
    $request->setViewId( $id_views );
    $request->setDateRanges($dateRange);
    $request->setDimensions(array($browser));
    $request->setMetrics(array($sessions));

    $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
    $body->setReportRequests( array( $request) );

    return $this->analyticsReporting->reports->batchGet( $body );

  }
}
