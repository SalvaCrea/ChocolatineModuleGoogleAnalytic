<?php
namespace Chocolatine\Modules\GoogleApi\view;


class reporting extends \Chocolatine\Pattern\Module\View
{
  public function get_report()
  {

    $id_views = '30257601';

    $query = new \Chocolatine\Modules\GoogleApi\Tools\QueryAnalyticReport();

    $data = $query->init( $id_views )
                  ->add_date()
                  ->add_metrics( 'ga:pageValue' )
                  ->add_metrics( 'ga:pageviews' )
                  ->add_metrics( 'ga:uniquePageviews' )
                  ->add_dimensions( 'ga:pagePath' )
                  ->run();

    \Chocolatine\add_block( 'content',
        $this->renderTemplate( 'GoogleApi@report.html.twig',
        array(
          'data' => $data
        ))
    );

  }
}
