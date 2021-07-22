<?php

namespace Drupal\crud\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;

/**
 * Class MydataController
 * @package Drupal\mymodule\Controller
 */
class MydataController extends ControllerBase
{

  /**
   * @return array
   */
  public function show($id)
  {

    $conn = Database::getConnection();

    $query = $conn->select('crud', 'm')
      ->condition('id', $id)
      ->fields('m');
    $data = $query->execute()->fetchAssoc();
    $full_name = $data['first_name'] . ' ' . $data['last_name'];
    $email = $data['email'];
    $phone = $data['phone'];
    
    return [
      '#type' => 'markup',
      '#markup' => "<h1>$full_name</h1><br>
                    <p>$email</p>
                    <p>$phone</p>
                    <p>$message</p>"
    ];
  }

}