<?php

namespace Drupal\crud\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;


class MyModuleForm extends FormBase
{
  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'mymodule_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $conn = Database::getConnection();
    $data = array();
    if (isset($_GET['id'])) {
      $query = $conn->select('crud', 'm')
        ->condition('id', $_GET['id'])
        ->fields('m');
      $data = $query->execute()->fetchAssoc();
    }

    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('first name'),
      '#required' => true,
      '#size' => 60,
      '#default_value' => (isset($data['first_name'])) ? $data['first_name'] : '',
      '#maxlength' => 128,
      '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12']
    ];
    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('last name'),
      '#required' => true,
      '#size' => 60,
      '#default_value' => (isset($data['last_name'])) ? $data['last_name'] : '',
      '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12']
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('email'),
      '#required' => true,
      '#default_value' => (isset($data['email'])) ? $data['email'] : '',
      '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12']
    ];
     
    $form['phone'] = [
      '#type' => 'tel',
      '#title' => $this->t('phone'),
      '#required' => true,
      '#default_value' => (isset($data['phone'])) ? $data['phone'] : '',
      '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12']
    ];
    $form['status'] = [
      '#type' => 'select',
      '#title' => 'Status',
      '#options' => [
        '1' => 'Active',
        '2' => 'Inactive',
      ],
      '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12'],
      '#default_value' => (isset($data['status'])) ? $data['status'] : '',
    ];
     
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('save'),
      '#buttom_type' => 'primary'
    ];
    return $form;
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    if (is_numeric($form_state->getValue('first_name'))) {
      $form_state->setErrorByName('first_name', $this->t('Error, The First Name Must Be A String'));
    }
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $picture = $form_state->getValue('picture');
    $data = array(
      'first_name' => $form_state->getValue('first_name'),
      'last_name' => $form_state->getValue('last_name'),
      'email' => $form_state->getValue('email'),
      'phone' => $form_state->getValue('phone'),
      'status' => $form_state->getValue('status')
      
    );

    

    if (isset($_GET['id'])) {
      // update data in database
      \Drupal::database()->update('crud')->fields($data)->condition('id', $_GET['id'])->execute();
    } else {
      // insert data to database
      \Drupal::database()->insert('crud')->fields($data)->execute();
    }

    // show message and redirect to list page
    \Drupal::messenger()->addStatus('Succesfully saved');
    $url = new Url('crud.display_data');
    $response = new RedirectResponse($url->toString());
    $response->send();
  }


}