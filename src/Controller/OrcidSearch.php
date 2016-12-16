<?php
/**
 * @file
 * Contains \Drupal\ngl_orcid\Controller\OrcidSearch.
 */
namespace Drupal\ngl_orcid\Controller;

use Drupal\ngl_orcid\Controller\OrcidController;

class OrcidSearch {

  public function idSearch($term) {
    // Initialize our return variable
    $searchResult = '';

    // Initialize the search class
    $search = new OrcidController();

    // Get the result
    $searchResult = $search->orcidSearchByID($term);

    // Return the results
    return $searchResult;
  }

  public function nameSearch($term) {
    // Initialize our return variable
    $searchResult = '';

    // Initialize the search class
    $search = new OrcidController();

    // Get the result
    $searchResult = $search->orcidSearchByString($term);

    // Return the results
    return $searchResult;
  }

}