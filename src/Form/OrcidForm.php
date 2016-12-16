<?php

/**
 * @file
 * Contains \Drupal\ngl_orcid\Form\OrcidForm.
 */

namespace Drupal\ngl_orcid\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class OrcidForm extends ConfigFormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormID() {
    return 'ngl_orcid_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Form constructor
    $form = parent::buildForm($form, $form_state);

    // Default settings
    $config = $this->config('ngl_orcid.settings');

    // Client ID field
    $form['client_id'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Client ID:'),
      '#default_value' => $config->get('ngl_orcid.client_id'),
      '#description' => $this->t('Enter your ORCID API Client ID.'),
    );

    // Client Secret field
    $form['client_secret'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Client Secret:'),
      '#default_value' => $config->get('ngl_orcid.client_secret'),
      '#description' => $this->t('Enter your ORCID API Client Secret.'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Method is required, but doesn't need to contain anything
  }

  /**
   * {@inheritdoc}.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Get any current configuration values
    $config = $this->config('ngl_orcid.settings');

    // Update values submitted by the form
    $config->set('ngl_orcid.client_id', $form_state->getValue('client_id'));
    $config->set('ngl_orcid.client_secret', $form_state->getValue('client_secret'));

    // Save the configuration
    $config->save();

    // Return the form
    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}.
   */
  public function getEditableConfigNames() {
    return [
      'ngl_orcid.settings',
    ];
  }

}