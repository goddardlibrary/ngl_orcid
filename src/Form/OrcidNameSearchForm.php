<?php
/**
 * @file
 * Contains \Drupal\ngl_orcid\Form\OrcidNameSearchForm.
 */

namespace Drupal\ngl_orcid\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ngl_orcid\Controller\OrcidSearch;

class OrcidNameSearchForm extends FormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'ngl_orcid_idsearch_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // ORCID string search field
    $form['ngl_orcid_term'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Enter a string to search for:'),
      '#description' => $this->t('Search ORCID for a string.'),
    );

    // Submit
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Search'),
    );

    // Return the form array
    return $form;
  }

  /**
   * {@inheritdoc}.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Method is required, but doesn't need to contain anything
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Get the term
    $term = $form_state->getValue('ngl_orcid_term');

    // Initialize the search class
    $search = new OrcidSearch();

    // Get the result
    $ngl_orcid_namesearch_result = $search->nameSearch($term);

    // Return the results
    ksm($ngl_orcid_namesearch_result);
  }

}