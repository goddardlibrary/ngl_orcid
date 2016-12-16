<?php
/**
 * @file
 * Contains \Drupal\ngl_orcid\Controller\OrcidController.
 */
namespace Drupal\ngl_orcid\Controller;

use Drupal\Component\Serialization\Json;

class OrcidController {

  public function content() {

    // Display some content on our landing page
    return array(
      '#type' => 'markup',
      '#markup' => t('Click on a tab to search ORCID by ID or Name.'),
    );

  }

  public function orcidSearchByString($searchString) {

    // Clean the search string
    $searchString = t($searchString);

    // Get the parameters for a search
    $params = $this->orcidTypeParameters('search');

    // Append the search string to the URL
    $params[0] .= '"' . $searchString . '"';

    // Perform the search
    $result = $this->orcidCurlFetch($params[0], $params[1], $params[2], $params[3]);

    // Return the search results
    return $result;
  }

  public function orcidSearchByID($orcid_id) {

    // Clean the search string
    $orcid_id = t($orcid_id);

    // Get the parameters for a search
    $params = $this->orcidTypeParameters('id');

    // Append the search string to the URL
    $params[0] .= $orcid_id . '/orcid-bio/';

    // Perform the search
    $result = $this->orcidCurlFetch($params[0], $params[1], $params[2], $params[3]);

    // Return the search results
    return $result;
  }

  public function orcidTypeParameters($type) {

    switch ($type) {
      case 'search':

        // Define the url
        $curlurl = 'https://api.sandbox.orcid.org/v1.2/search/orcid-bio/?q=';

        // Get the token
        $token_result = $this->orcidTokenFetch('search');

        // Define the headers
        $curlheaders = array();
        $curlheaders[] = "Content-Type: application/orcid+xml";
        $curlheaders[] = "Authorization: Bearer " . $token_result;

        // Define the post fields
        $curlpostfields = '';

        break;
      case 'id':

        // Define the url
        $curlurl = 'https://pub.sandbox.orcid.org/v1.2/';

        // Get the token
        $token_result = $this->orcidTokenFetch('id');

        // Define the headers
        $curlheaders = array();
        $curlheaders[] = "Content-Type: application/orcid+xml";
        $curlheaders[] = "Authorization: Bearer " . $token_result;

        // Define the post fields
        $curlpostfields = '';

        break;
    }

    $result = array();
    $result[] = $curlurl;
    $result[] = $curlheaders;
    $result[] = $curlpostfields;
    $result[] = $type;

    return $result;
  }

  public function orcidTokenFetch($type) {
    // Initialize our return variable
    $token = '';

    // Load the configuration
    $config = \Drupal::config('ngl_orcid.settings');

    // Get the Client ID
    $client_id = $config->get('ngl_orcid.client_id');

    // Get the Client Secret
    $client_secret = $config->get('ngl_orcid.client_secret');

    if ($type == 'search' || $type == 'id') {

      // Define the URL
      $curlurl = 'https://sandbox.orcid.org/oauth/token';

      // Initialize the header array
      $curlheaders = array();

      // Define the array elements
      $curlheaders[] = "Accept: application/json";

      // Define the post fields
      $curlpostfields = 'client_id=' . $client_id . '&client_secret=' . $client_secret . '&scope=/read-public&grant_type=client_credentials';

      // Get the token with curl
      $token = $this->orcidCurlFetch($curlurl, $curlheaders, $curlpostfields);

    }

    // Get the access token from our json
    $token = Json::decode($token)['access_token'];

    // Return the token
    return $token;
  }

  public function orcidCurlFetch($curlurl, $curlheaders = array(), $curlpostfields = '', $type = '') {

    // Initialize curl
    $curl = curl_init();

    // Define the options
    curl_setopt($curl, CURLOPT_URL, $curlurl);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $curlheaders);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curlpostfields);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // return web page
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HEADER, false); // don't return headers
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // follow redirects
    curl_setopt($curl, CURLOPT_MAXREDIRS, 10); // stop after 10 redirects
    curl_setopt($curl, CURLOPT_ENCODING, ""); // handle compressed
    curl_setopt($curl, CURLOPT_USERAGENT, "NGL ORCID"); // name of client
    curl_setopt($curl, CURLOPT_AUTOREFERER, true); // set referrer on redirect
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 120); // time-out on connect
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // time-out on response

    // Set additional options for special circumstances
    if ($type == 'search' || $type == 'id') {
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    }

    // Execute our curl
    $curlresp = curl_exec($curl);

    // Check for errors
    if (curl_errno($curl)) {
      // Return our error message
      $curlresp = 'An error has occurred: ' . curl_error($curl);
    }

    // Close curl
    curl_close($curl);

    // Return our curl response
    return $curlresp;
  }

}