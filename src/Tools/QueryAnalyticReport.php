<?php


namespace Chocolatine\Modules\GoogleApi\Tools;

/**
 *  Class Use for create QueryAnalytic
 */

class QueryAnalyticReport{

      public $alias = "AnalyticReport";

      /**
       *  Container Date Start date search
       * @var string
       */
      public $containerDate = array();
      /**
       * Container Metrix
       * @var array
       */
      public $containerMetric = array();
      /**
       * Container Dimension
       * @var array
       */
      public $containerDimension = array();
      /**
       * The id view google analitics
       * @var string
       */
      public $idView;

      public function __construct(){

          $module = \Chocolatine\get_module( 'GoogleApi' );
          $this->client_google = $module->component->Connection->connection();
          $this->analyticsReporting = $module->component->Report->connection();

      }
      /**
       * init the query
       * @param  string $idView The id view google Analyti
       */
      public function init( $idView ){
          $this->idView = $idView;
          return $this;
      }
      /**
       * Create A requete http for gettting data
       * @return [type] [description]
       */
      public function run(){

        // Create the ReportRequest object.
        $request = new \Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId( $this->idView );

        if ( !empty( $this->containerDate ) ) {
            $request->setDateRanges( $this->containerDate[0] );
        }

        if ( !empty( $this->containerDimension ) ) {
            $request->setDimensions( $this->containerDimension );
        }

        if ( !empty( $this->containerMetric ) ) {
            $request->setMetrics( $this->containerMetric );
        }

        $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests( array( $request ) );
        $report = $this->analyticsReporting->reports->batchGet( $body );
        return $this->clean_report( $report );

      }
      public function clean_report( $reports ){

          for ( $reportIndex = 0; $reportIndex < count( $reports ); $reportIndex++ ) {
            $report = $reports[ $reportIndex ];
            $header = $report->getColumnHeader();
            $dimensionHeaders = $header->getDimensions();
            $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
            $rows = $report->getData()->getRows();

            $return = [];

            for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
              $data = [];
              $row = $rows[ $rowIndex ];
              $dimensions = $row->getDimensions();
              $metrics = $row->getMetrics();
              for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) {
                $data[$dimensionHeaders[$i]] = $dimensions[$i];
              }

              for ($j = 0; $j < count($metrics); $j++) {
                $values = $metrics[$j]->getValues();
                for ($k = 0; $k < count($values); $k++) {
                  $entry = $metricHeaders[$k];
                  $data[$entry->getName()] = $values[$k];
                }
              }

              $return []= $data;
            }
          }

          return $return;
      }
      public function add_date( $startDate = '30daysAgo', $endDate = 'today'){
        // Create the DateRange object.
        $dateRange = new \Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate( $startDate );
        $dateRange->setEndDate( $endDate );

        $this->containerDate []= $dateRange;

        return $this;
      }
      public function add_metrics( $expression = "ga:sessions"){
        // Create the Metrics object.
        $metrics = new \Google_Service_AnalyticsReporting_Metric();
        $metrics->setExpression( $expression );
        // $metrics->setAlias( $this->alias );

        $this->containerMetric []= $metrics;

        return $this;
      }
      public function add_dimensions( $expression = "ga:browser"){
        // Create the Metrics object.
        $metrics = new \Google_Service_AnalyticsReporting_Dimension();
        $metrics->setName( $expression );

        $this->containerDimension []= $metrics;

        return $this;
      }

}
