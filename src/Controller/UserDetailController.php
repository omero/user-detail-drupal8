<?php

namespace Drupal\user_detail\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserDetailController extends ControllerBase implements ContainerInjectionInterface 
{
  protected $user;
  protected $file; 

  public function __construct(EntityManager $entity_manager)
  {
    $this->user = $entity_manager->getStorage('user');
    $this->file = $entity_manager->getStorage('file');
  }

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity.manager')
    );
  }

  /**
   * userGet
   * @return string
   */
  public function userGet($uid)
  {
    $account = $this->user->load($uid);
    if (isset($account)) {
      $picture = $account->get('user_picture')->getValue();
      $url = $this->file->load($picture[0]['target_id']);
      $arr_user = $account->toArray();
      $json_user = [
        'uid' => $arr_user['uid'][0]['value'],
        'mail' => $arr_user['mail'][0]['value'],
        'name' => $arr_user['name'][0]['value'],
        'created' => $arr_user['created'][0]['value'],
        'login' => $arr_user['login'][0]['value'],
        'field_name' => $arr_user['field_name'][0]['value'],
        'field_last_name' => $arr_user['field_last_name'][0]['value'],
        'field_twitter' => $arr_user['field_twitter'][0]['value'],
        'field_facebook' => $arr_user['field_facebook'][0]['value'],
        'field_description' => $arr_user['field_description'][0]['value'],
        'user_picture' => $url->url(),
      ];
      
      return new JsonResponse($json_user);
    }

    return new JsonResponse('User Null :P');
  }
}
