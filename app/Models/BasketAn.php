<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;
require_once(app_path().'/fonction.php');




class BasketAn extends Model
{
  public $product_id_rules = '\.a-z0-9_-';
  public $product_name_rules = '\w \-\.\:';
  public $product_name_safe = TRUE;
 

  public function clear_cart($isMobile = null, $id_user = null)
  {
    if (is_null($isMobile)) {
      $id_user = $this->session->userdata['id_user'];
    }

    $user = $this->users->findUserById($id_user)->result_array()[0];
    $id_family = $this->users->findFamilyIdByUserId($id_user)->result_array()[0]['id_family'];
    $newSql = "DELETE sc.*, o.* FROM `shopping_carts` sc INNER JOIN options_cart o ON o.id_option = sc.id_option INNER JOIN users u ON u.id_user = o.id_user WHERE u.id_family = $id_family";
    $sql = "DELETE sc.*, o.* FROM `shopping_carts` sc INNER JOIN options_cart o ON o.id_option = sc.id_option WHERE o.id_user = $id_user";
    if ($user['family_level'] == "parent") {
      $this->db->query($newSql);
      $this->blog_posts->checkCartOptions();
    }
  }

  public function clear__all_carts($isMobile = null)
  {
    $this->db->query("DELETE FROM `shopping_carts`");
    $this->db->query("DELETE FROM `options_cart`");
  }

  public function get_cart($isMobile = null)
{
    if (is_null($isMobile)) {
        if (auth()->check()) {
            $id_user = auth()->id();
            $id_family = findFamilyIdByUserId($id_user);
            return $this->_get_cart($id_user, $id_family);
        } else {
            return [];
        }
    } else {
        $id_user = auth()->id(); // assuming $id_user is set previously
        $id_family = $this->users->findFamilyIdByUserId($id_user)->result_array()[0]['id_family'];
        return $this->_get_cart_mobile($id_user, $id_family);
    }
}

protected function _get_cart($id_user, $id_family)
{
    $newsql = "SELECT sc.id_purchaser, sc.id, sc.quantity, sc.name, sc.price, sc.id_option, sc.rowid, sc.subtotal, sc.date FROM shopping_carts sc INNER JOIN options_cart o ON o.id_option = sc.id_option INNER JOIN users u ON o.id_user = u.user_id WHERE u.family_id = $id_family";
    $sql = "SELECT sc.id_purchaser, sc.id, sc.quantity, sc.name, sc.price, sc.id_option, sc.rowid, sc.subtotal, sc.date FROM shopping_carts sc INNER JOIN options_cart o ON o.id_option = sc.id_option WHERE o.user_id = $id_user";
    
    $result = DB::select($newsql);

    $cart = [];

    foreach ($result as $key => $item) {
        $newitem = [];

        if (is_array($item)) {
            foreach ($item as $field => $value) {
                if ($field == "id_option") {
                    $options = $this->get_option($value);
                    $newitem["options"] = $options->first();
                } else {
                    $newitem[$field] = $value;
                }
            }
        }

        $cart[$item->rowid] = $newitem;
    }

    return $cart;
}

  protected function _get_cart_mobile($id_user, $id_family)
  {
    $newsql = "SELECT sc.id_purchaser, sc.id, sc.quantity,sc.name,sc.price,sc.id_option,sc.rowid,sc.subtotal,sc.date FROM shopping_carts sc INNER JOIN options_cart o ON o.id_option = sc.id_option INNER JOIN users u ON o.id_user = u.id_user WHERE u.id_family = $id_family";
    $sql = "SELECT sc.id_purchaser, sc.id, sc.quantity,sc.name,sc.price,sc.id_option,sc.rowid,sc.subtotal,sc.date FROM shopping_carts sc INNER JOIN options_cart o ON o.id_option = sc.id_option WHERE o.id_user = $id_user";
    $result = $this->db->query($newsql);
    $cart = array();
    foreach ($result->result_array() as $key => $item) {
      $newitem = array();
      if (is_array($item)) {
        foreach ($item as $field => $value) {
          if ($field == "id_option") {
            $options = $this->get_option($value)->result_array();
            $newitem["options"] = $options[0];
          } else {
            $newitem[$field] = $value;
          }
        }
      }
      array_push($cart, $newitem);
    }
    return $cart;
  }
  public function get_option($id_option)
  {
    return $this->db->query("SELECT * FROM options_cart o WHERE o.id_option = $id_option");
  }

  public function insert($items = array())
  {
    if (!is_array($items) or count($items) === 0) {
      log_message('error', 'The insert method must be passed an array containing data.');
      return FALSE;
    }

    $save_cart = FALSE;
    if (isset($items['id'])) {
      if (($rowid = $this->_insert($items))) {
        $save_cart = TRUE;
      }
    } else {
      foreach ($items as $val) {
        if (is_array($val) && isset($val['id'])) {
          if ($this->_insert($val)) {
            $save_cart = TRUE;
          }
        }
      }
    }
    return FALSE;
  }

  public function has_options($row_id = '')
  {
    return (isset($this->get_cart()[$row_id]['options']) && count($this->get_cart()[$row_id]['options']) !== 0);
  }

  public function product_options($row_id = '')
  {
    return isset($this->get_cart()[$row_id]['options']) ? $this->get_cart()[$row_id]['options'] : array();
  }

  protected function _insert($items = array())
  {
    if (!is_array($items) or count($items) === 0) {
      log_message('error', 'The insert method must be passed an array containing data.');
      return FALSE;
    }

    if (!isset($items['id'], $items['quantity'], $items['price'], $items['name'])) {
      log_message('error', 'The cart array must contain a product ID, quantity, price, and name.');
      return FALSE;
    }
    $items['quantity'] = (float) $items['quantity'];

    if ($items['quantity'] == 0) {
      return FALSE;
    }

    $items['price'] = (float) $items['price'];

    if (isset($items['options']) && count($items['options']) > 0) {
      $rowid = md5($items['id'] . serialize($items['options']));
    } else {
      $rowid = md5($items['id']);
    }

    $items['rowid'] = $rowid;
    $this->set_cart($rowid, $items);
    return $rowid;
  }

  public function set_cart($rowid, $items)
  {
    $options = $items["options"];
    $cart = array();
    $cart['id_purchaser'] = $rowid;
    foreach ($items as $key => $value) {
      if (!is_array($value)) {
        $cart[$key] = $value;
      } else { //Passe par ici

        $this->db->insert('options_cart', $options); //options = options de l'array, donc ID_user, attestation fiscale etc...
        $id_option = $this->db->insert_id();
        $cart["id_option"] = $id_option;


      }
    }
    $exist = false;

    $cartArray = $this->get_cart();


    foreach ($cartArray as $key => $value) {



      if ($key == $rowid) {
        if ($value['price'] < 0) {
          $this->db->query("DELETE FROM `shopping_carts` WHERE `rowid` = '$rowid'");
          $this->db->insert('shopping_carts', $cart);
        }
        $exist = true;
        $this->db->insert('shopping_carts', $cart);

      }
    }
    if (!$exist) {
      // devrais passer par ici
      $this->db->insert('shopping_carts', $cart);

    }
  }

  public function format_number($n = '')
  {
    return ($n === '') ? '' : number_format((float) $n, 2, '.', ',');
  }

  public function total()
  {
    if ($this->session->userdata('status') == "logged_in") {
      $id_family = $this->session->userdata['id_family'];
      $sql = "SELECT ROUND(SUM(sc.quantity * sc.price ), 2) as Total FROM `shopping_carts` sc INNER JOIN options_cart o ON o.id_option = sc.id_option inner join users u ON u.id_user = o.id_user WHERE u.id_family =  '$id_family'";
      $result = $this->db->query($sql)->result_array()[0]["Total"];
      return $result;
    } else {
      return 0;
    }
  }

  public function updateitems($items = array())
  {
    if (!is_array($items) or count($items) === 0) {
      return FALSE;
    }

    $save_cart = FALSE;
    if (isset($items['rowid'])) {
      if ($this->_update($items) === TRUE) {
        $save_cart = TRUE;
      }
    } else {
      foreach ($items as $val) {
        if (is_array($val) && isset($val['rowid'])) {
          if ($this->_update($val) === TRUE) {
            $save_cart = TRUE;
          }
        }
      }
    }

    if ($save_cart === TRUE) {
      $this->_save_cart();
      return TRUE;
    }

    return FALSE;
  }


  protected function _update($items = array())
  {
    if (!isset($items['rowid'], $this->get_cart()[$items['rowid']])) {
      return FALSE;
    }

    if (isset($items['quantity'])) {
      $items['quantity'] = (float) $items['quantity'];
      if ($items['quantity'] == 0) {
        echo "<script>alert('Executé lors de l'update')</script>";
        return TRUE;
      }
    }

    $keys = array_intersect(array_keys($this->get_cart()[$items['rowid']]), array_keys($items));
    if (isset($items['price'])) {
      $items['price'] = (float) $items['price'];
    }

    foreach (array_diff($keys, array('id', 'name')) as $key) {
      $this->get_cart()[$items['rowid']][$key] = $items[$key];
    }

    return TRUE;
  }

  public function isOldCart($datetime)
  {
    $outdated_days = 7;
    $now = new DateTime('NOW');
    $date = date_create_from_format('Y-m-d H:i:s', $datetime);
    $interval = $now->diff($date);
    $days = (int) $interval->format('%R%a');
    if ($days < -7) {
      return true;
    } else {
      return false;
    }
  }

  public function deleteOldCartItems()
  {
    $sql = "select * from shopping_carts ";
    $result = $this->db->query($sql)->result_array();
    $oldItems = array();
    foreach ($result as $item) {
      if ($this->isOldCart($item['date'])) {
        $this->delete_cart_item($item['id_purchaser']);
      }
    }
    return $oldItems;
  }

  public function delete_cart_item($id)
  {
    $this->db->query("DELETE sc.*, o.* FROM `shopping_carts` sc INNER JOIN options_cart o ON o.id_option = sc.id_option WHERE sc.id_purchaser = '$id' ");
  }

  public function buy($id_shop_article, $id_user = null,  $quantity = null, $isMobile = null)
  {
    return $this->_buy($id_shop_article, $id_user, $quantity);
  }

  public function recursive_data_article($id_shop_article, $firstname, $lastname, $id_user_shop, $quantity)
  {
    $article = $this->shop_articles->parametre('id_article_inscription')->result_array();
    $id_article = $article[0]['id_article_inscription'];
    $article2 = $this->shop_articles->parametre('reduction_famille')->result_array();
    $montant = $article2[0]['reduction_famille'];

    $test = $this->shop_articles->count_bills_family($id_user_shop, $id_article);
    $num = $this->shop_articles->get_article_basket_depending($id_shop_article, $id_user_shop);

    $data = array();
    $compeur = 1;
    $num2 = $this->shop_articles->get_article_basket_depending($id_shop_article, $id_user_shop);
    while ($num->result() && $num2->result()) :
      $num2 = $this->shop_articles->get_article_basket_depending($id_shop_article, $id_user_shop);
      foreach ($num2->result() as $li) :

        if ($this->shop_articles->count_max_per_user_basket($li->id_shop_article, $id_user_shop, $quantity) === FALSE) :
          $data[$compeur] = array(
            'id' => $li->id_shop_article,
            'quantity' => 1,
            'name' => $li->title,
            'price' => $li->TTC,
            'options' => array( 'id_user' => $id_user_shop, 'Attestation_fiscale' => $li->certificate, 'HT' => $li->HT, 'TVA' => $li->TVA,  'Pour' => $firstname . ' ' . $lastname, 'Statut' => 'obligatoire', 'ref' => $li->ref, 'image' => $li->image),
            'subtotal' => ($li->TTC) * 1,
            'date' => date("Y-m-d H:i:s")
          );
          if ($test > 0 && $li->id_shop_article == $id_article) :
            $data = array(
              'id' => 'reductfami',
              'quantity' => 1,
              'name' => 'Reduction famille',
              'price' => -$montant,
              'options' => array('id_user' => $id_user_shop, 'Attestation_fiscale' => '', 'TVA' => '' . ' %',  'Pour' => '', 'Statut' => 'obligatoire', 'ref' => '', 'image' => '/Resources/Images/Logos/Icons/Reduc-Famille.png'),
              'subtotal' => 1 * (-$montant),
              'date' => date("Y-m-d H:i:s")
            );
            $this->insert($data);
          endif;
        else :
          break;
        endif;
      endforeach;
      if (isset($data[$compeur]['id'])) :
        $id_shop_article = $data[$compeur]['id'];
        $compeur++;
      else : break;
      endif;

    endwhile;
    return $data;
  }

  protected function _buy($id_shop_article, $id_user, $quantity)
  {

    if ($this->session->userdata('status') == "logged_in") :
      $redfamSamil = false;
      $users_in_basket = [];
      foreach ($this->get_cart() as $items) {
        array_push($users_in_basket, $items['options']['id_user']);
      }
      if (in_array($id_user, $users_in_basket)) {
        $redfamSamil = true;
      }
      $product = $this->shop_articles->get_article_basket($id_shop_article);
      $id_user_shop = $id_user;
      $redirection = $this->input->post('redirection');

      $info['array'] = $this->shop_articles->select_data_user($id_user_shop);
      $row = $info['array']->row();
      $data['page'] = 'shop';

      if ($this->shop_articles->count_max_per_user_basket($id_shop_article, $id_user_shop, $quantity) === FALSE) :

        $data['product'][0] = array(
          'id' => $product[0]->id_shop_article,
          'quantity' => $quantity,
          'name' => $product[0]->title,
          'price' => $product[0]->TTC,
          'options' => array('id_user' => $id_user_shop, 'Attestation_fiscale' => $product[0]->certificate, 'TVA' => $product[0]->TVA . ' %',  'Pour' => $row->firstname . ' ' . $row->lastname, 'Statut' => 'non obligatoire', 'ref' => $product[0]->ref, 'image' => $product[0]->image),
          'subtotal' => ($quantity) * ($product[0]->TTC),
          'date' => date("Y-m-d H:i:s")
        );
        $array = $this->recursive_data_article($id_shop_article, $row->firstname, $row->lastname, $id_user_shop, $quantity);
        if (!empty($array)) :
          foreach ($array as $arr) :
            array_push($data['product'], $arr);
          endforeach;
        endif;

        $this->insert($data['product']);

        $reductfami = 1;
        $nbreducfamille = 0;
        $vartest = 0;
        $id_user_test = 0;
        $id_user_test2 = 0;

        $article = $this->shop_articles->parametre('id_article_inscription')->result_array();
        $id_article = $article[0]['id_article_inscription'];
        $reduction_famille = $this->shop_articles->parametre('reduction_famille')->result_array();
        $montant = $reduction_famille[0]['reduction_famille'];

        $test = $this->shop_articles->count_bills_family($id_user_shop, $id_article);
        $test_other_fami = $this->shop_articles->count_bills_family_others($id_user_shop, $id_article);

        $totalpaniertemp = 0;

        foreach ($this->get_cart() as $items) {
          $id_user_test2 = $items['options']['id_user'];
          if ($id_user_test !== $id_user_test2 && $id_user_test2 < 1000000) :
            $id_user_test = $id_user_test2;
            $vartest++;
          endif;
          $totalpaniertemp = $totalpaniertemp + $items['price'];
        }

        //Si c'est le cas et si l'utilisateur n'est pas déjà inscrit
        if ($test == 0 && $vartest > 1) :

          $result = $this->shop_articles->verify_depend($id_shop_article);
          if (in_array($id_article, $result)) :

            $test_reduc = 0;

            foreach ($this->get_cart() as $items) {

              //Vérifie si on a l'article sur lequel s'applique la réduc
              if ($items['id'] == $id_article && $redfamSamil == false) {
                $test_reduc = 2;
                $nbreducfamille++;
              } elseif ($items['id'] == 'reductfami') {
              }
            }
            $test_reduc = $test_reduc - 1;
            if ($totalpaniertemp > $montant  && $redfamSamil == false) {
              $datamind = array(
                'id' => 'reductfami',
                'quantity' => $test_reduc,
                'name' => 'Reduction famille',
                'price' => -$montant,
                'options' => array('id_user' => $id_user_shop, 'Attestation_fiscale' => '', 'TVA' => '' . ' %', 'Pour' => '', 'Statut' => 'obligatoire', 'ref' => '', 'image' => '/Resources/Images/Logos/Icons/Reduc-Famille.png'),
                'subtotal' => ($test_reduc) * (-$montant),
                'date' => date("Y-m-d H:i:s")
              );
              $this->insert($datamind);
            };
          endif;
        //Si d'autres membres de sa famille ont sont déjà inscrits
        elseif ($test_other_fami > 0) :
          foreach ($this->get_cart() as $items) {

            //Vérifie si on a l'article sur lequel s'applique la réduc
            if ($items['id']  == $id_article && !in_array($id_user_shop, $users_in_basket)) :
              $reductfami++;
              if (($reductfami == 2) && ($totalpaniertemp > $montant)) :
                $data = array(
                  'id' => 'reductfami',
                  'quantity' => 1,
                  'name' => 'Reduction famille',
                  'price' => -$montant,
                  'options' => array('id_user' => $id_user_shop, 'Attestation_fiscale' => '', 'TVA' => '' . ' %',  'Pour' => '', 'Statut' => 'obligatoire', 'ref' => '', 'image' => '/Resources/Images/Logos/Icons/Reduc-Famille.png'),
                  'subtotal' => 1 * (-$montant),
                  'date' => date("Y-m-d H:i:s")
                );
                $this->insert($data);
              endif;
            endif;
          }
        endif;

        //Réductions automatiques dès l'ajout du 1er article
        $reducauto = 0;
        $id_reduc_auto = 0;
        foreach ($this->get_cart() as $item) :
          if ($item['options']['Statut'] == 'reducauto') :
            $reducauto++;
            $id_reduc_auto = $item['options']['id_reduction'];
          endif;
        endforeach;
        if ($this->total() > 0) :
          //Vérif des reducs auto existantes
          $array_all = $this->shop_articles->check_all_reduc_auto()->result_array();
          $array_reduction = array();
          foreach ($array_all as $all) {

            $array_reduction2 = $this->shop_articles->last_check_quantity_reduction($all['id_shop_reduction']);

            if (!empty($array_reduction2)) {
              array_push($array_reduction, $array_reduction2);
            }
          }
          if (!empty($array_reduction)) {
            foreach ($this->get_cart() as $item) :
              if ($item['options']['Statut'] == 'reducauto') :
              endif;
            endforeach;
          }

          if (!empty($array_reduction)) {
            $new = array();
            $new2 = array();
            foreach ($array_reduction as $reduc) {
              foreach ($reduc as $reduc2) {
                $new2['id_shop_reduction'] = $reduc2['id_shop_reduction'];
                $new2['id_user'] = $reduc2['id_user'];
                array_push($new, $new2);
              }
            }
            $sortArray = array();
            foreach ($new as $person) {
              foreach ($person as $key => $value) {
                if (!isset($sortArray[$key])) {
                  $sortArray[$key] = array();
                }
                $sortArray[$key][] = $value;
              }
            }
            $orderby = "id_user";
            if (!empty($sortArray)) {
              array_multisort($sortArray[$orderby], SORT_DESC, $new);
            }
            $result_eacher_user = array();
            foreach ($new as $data) {
              $id = $data['id_user'];
              if (isset($result_eacher_user[$id])) {
                $result_eacher_user[$id][] = $data;
              } else {
                $result_eacher_user[$id] = array($data);
              }
            }
            $array_fi = array();
            foreach ($result_eacher_user as $each) {
              $total = $this->shop_articles->check_total_price($each, $id_user);
              array_push($array_fi, $total);
            }
            $count_reduc_family = 0;
            foreach ($array_fi as $reduction) {
              $GetReductionsSQLString = 'SELECT `id_shop_article` FROM `liaison_shop_articles_shop_reductions` WHERE `id_shop_reduction` = ' . $reduction["id_shop_reduction"];
              $reductionsArraySamil = $this->db->query($GetReductionsSQLString)->result_array();
              $newReductionsArraySamil = array();
              foreach ($reductionsArraySamil as $redSamil) {

                array_push($newReductionsArraySamil, $redSamil['id_shop_article']);
              }
              $query = $this->shop_articles->data_user($reduction['id_user']);

              if ($query->row() != NULL) {
                $row = array();
                $row = $query->row_array();

                $usernames = $row['firstname'] . ' ' . $row['lastname'];
                //Info de la reduction
                $checkreduct = $this->shop_articles->check_shop_reduction($reduction['id_shop_reduction']);
                $redSamilInfo = $checkreduct->result_array()[0];
                if ($checkreduct->result()) :
                  $row = $checkreduct->row();
                  $test = 0;
                  //Vérif s'il y a une réduction en pourcentage
                  if ($row->percentage > 0 && $row->value > 0) :

                    $value = - ($row->percentage / 100) * $this->format_number($this->total()) - $row->value;
                    $data = array(
                      'id' => 'reducauto' . $row->id_shop_reduction,
                      'quantity' => 1,
                      'name' => $row->description,
                      'price' => $value,
                      'options' => array('id_user' => $reduction['id_user'], 'Attestation_fiscale' => '', 'TVA' => '' . ' %', 'Pour' => $usernames, 'Statut' => 'reducauto', 'ref' => 'R_AUTO_' . $row->id_shop_reduction, 'image' => '/Resources/Images/Logos/Icons/Reduc-Diverse.png', 'id_reduction' => $row->id_shop_reduction),
                      'subtotal' => 1 * $value,
                      'date' => date("Y-m-d H:i:s")
                    );
                    $this->insert($data);
                  elseif ($row->value > 0 || $row->percentage > 0) :
                    if ($reduction['id_shop_reduction'] == 2 || $reduction['id_shop_reduction'] == 3 || $reduction['id_shop_reduction'] == 4) :
                      $valueReduction = 0;
                      $pan = $this->get_cart();
                      foreach ($pan as $item) :

                        if ($item['options']['id_user'] == $reduction['id_user']  && in_array($item['id'], $newReductionsArraySamil)) :
                          $valueReduction += $item['price'];
                        endif;
                        if ($item['id'] == 'reductfami' && $count_reduc_family == 1) :
                          $count_reduc_family = 2;
                        elseif ($count_reduc_family == 0) :
                          $count_reduc_family = 1;
                        endif;
                      endforeach;
                      if ($valueReduction > 0) :
                        $data = array(
                          'id' => 'reducauto' . $row->id_shop_reduction,
                          'quantity' => 1,
                          'name' => $row->description,
                          'price' => (-$row->percentage / 100) * $valueReduction,
                          'options' => array('id_user' => $reduction['id_user'], 'Attestation_fiscale' => '', 'TVA' => '' . ' %', 'Pour' => $usernames, 'Statut' => 'reducauto', 'ref' => $redSamilInfo["code"], 'image' => '/Resources/Images/Logos/Icons/Reduc-COVID.png', 'id_reduction' => $row->id_shop_reduction),
                          'subtotal' => 1 * ((-$row->percentage / 100) * $valueReduction),
                          'date' => date("Y-m-d H:i:s")
                        );
                        $this->insert($data);
                      endif;

                    elseif ($reduction['id_shop_reduction'] == 5 || $reduction['id_shop_reduction'] == 6 || $reduction['id_shop_reduction'] == 7 || $reduction['id_shop_reduction'] == 8 || $reduction['id_shop_reduction'] == 9) :
                      $valueReduction = 0;
                      foreach ($this->get_cart() as $item) :
                        if ($item['options']['id_user'] == $reduction['id_user']   && in_array($item['id'], $newReductionsArraySamil)) :
                          $valueReduction += $item['price'];
                        endif;
                        if ($item['id'] == 'reductfami' && $count_reduc_family = 1) :
                          $count_reduc_family = 2;
                        elseif ($count_reduc_family = 0) :
                          $count_reduc_family = 1;
                        endif;
                      endforeach;
                      if ($valueReduction > 0) :
                        $data = array(
                          'id' => 'reducauto' . $row->id_shop_reduction,
                          'quantity' => 1,
                          'name' => $row->description,
                          'price' => (-$row->percentage / 100) * $valueReduction - $row->value,
                          'options' => array('id_user' => $reduction['id_user'], 'Attestation_fiscale' => '', 'TVA' => '' . ' %', 'Pour' => $usernames, 'Statut' => 'reducauto', 'ref' => $redSamilInfo["code"], 'image' => '/Resources/Images/Logos/Icons/Reduc-Diverse.png', 'id_reduction' => $row->id_shop_reduction),
                          'subtotal' =>  1 * ((-$row->percentage / 100) * $valueReduction - $row->value),
                          'date' => date("Y-m-d H:i:s")
                        );
                        $this->insert($data);
                      endif;

                    elseif ($reduction['id_shop_reduction'] == 1) :
                      $valueReduction = 0;
                      foreach ($this->get_cart() as $item) :
                        if ($item['options']['id_user'] == $reduction['id_user']  && in_array($item['id'], $newReductionsArraySamil)) :
                          $valueReduction += $item['price'];
                        endif;
                      endforeach;
                      if ($valueReduction > 0) :
                        $data = array(
                          'id' => 'reducauto' . $row->id_shop_reduction,
                          'quantity' => 1,
                          'name' => $row->description,
                          'price' => (-$row->percentage / 100) * $valueReduction,
                          'options' => array('id_user' => $reduction['id_user'], 'Attestation_fiscale' => '', 'TVA' => '' . ' %', 'Pour' => $usernames, 'Statut' => 'reducauto', 'ref' => $redSamilInfo["code"], 'image' => '/Resources/Images/Logos/Icons/Reduc-Jpo.png', 'id_reduction' => $row->id_shop_reduction),
                          'subtotal' => ((-$row->percentage / 100) * $valueReduction) * 1,
                          'date' => date("Y-m-d H:i:s")
                        );
                        $this->insert($data);
                      endif;

                    elseif ($row->value > 0) :
                      $data = array(
                        'id' => 'reducauto' . $row->id_shop_reduction,
                        'quantity' => 1,
                        'name' => $row->description,
                        'price' => -$row->value,
                        'options' => array('id_user' => $reduction['id_user'], 'Attestation_fiscale' => '', 'TVA' => '' . ' %', 'Pour' => $usernames, 'Statut' => 'reducauto', 'ref' => 'R_AUTO_' . $row->id_shop_reduction, 'image' => '/Resources/Images/Logos/Icons/Reduc-Diverse.png', 'id_reduction' => $row->id_shop_reduction),
                        'subtotal' => (-$row->value) * 1,
                        'date' => date("Y-m-d H:i:s")
                      );
                      $this->insert($data);
                    elseif ($row->percentage > 0) :
                      $data = array(
                        'id' => 'reducauto' . $row->id_shop_reduction,
                        'quantity' => 1,
                        'name' => $row->description,
                        'price' => (-$row->percentage / 100) * $this->format_number($this->total()),
                        'options' => array('id_user' => $reduction['id_user'], 'Attestation_fiscale' => '', 'TVA' => '' . ' %', 'Pour' => $usernames, 'Statut' => 'reducauto', 'ref' => 'R_AUTO_' . $row->id_shop_reduction, 'image' => '/Resources/Images/Logos/Icons/Reduc-Diverse.png', 'id_reduction' => $row->id_shop_reduction),
                        'subtotal' => 1 * ((-$row->percentage / 100) * $this->format_number($this->total())),
                        'date' => date("Y-m-d H:i:s")
                      );
                      $this->insert($data);
                    endif;
                  endif;
                endif;
              }
            }
          }

        /*REDUC JPO*/
        /* foreach ($this->get_cart() as $item):
                          if ($item['id'] == 'reductjpo'):
                              //$this->update(array('rowid' => $item['rowid'], 'quantity' => 0));
                          endif;
                        endforeach;
                              $somme = $this->total();
                              $data = array(
                                  'id' => 'reductjpo',
                                  'quantity' => 1,
                                  'name' => 'Réduction Exceptionnelle 5% (25 Juin)',
                                  'price' => (-$somme*0.05),
                                  'options' => array('id_user' => 1000000, 'Attestation_fiscale' => '', 'TVA' => '' . ' %', 'Pour' => '', 'Statut' => 'obligatoire', 'ref' => 'Reduction_5%', 'image' => '/Resources/Images/Logos/Icons/Reduc-Jpo.png')
                                  'subtotal' =>(-$somme*0.05) * 1,
                                  'date' => date("Y-m-d H:i:s")
                              );
                              $this->insert($data);

                              */
        endif;
      else :
        $flash = array(

          'limit' => '<div data-closable class="alert-box callout warning">
                      <i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Vous ne pouvez pas inscrire la même personne plusieurs fois au même cours !&nbsp;&nbsp;<a href="' . base_url('index.php/shop/basket_content') . '"><span color="#000000"><img src="' . base_url('image/panier.png') . '" width="40px" /> Aller au Panier</span></a><button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                        <span aria-hidden="true">&CircleTimes;</span>
                      </button></div>'
        );
        $this->session->set_flashdata($flash);
        if ($redirection != "Passer au paiement") {
          redirect('shop/details/' . $product[0]->url_shop_article);
        } else {
          redirect('shop/basket_content');
        };
      endif;
      $flash = array(

        'message' => '<div data-alert class="alert-box success "> Produit ajouté <a href="#" class="close">&times;</a></div>'
      );
      $this->session->set_flashdata($flash);
      if ($redirection != "Passer au paiement") {
        if ($redirection != "Passer au paiement") {
          redirect('shop/details/' . $product[0]->url_shop_article);
        } else {
          redirect('shop/basket_content');
        }
      } else {
        redirect('shop/basket_content');
      };
    else :
      redirect('');

    endif;
  }

  public function buy_Gala($id_shop_article, $leGrosArray,$placesInfo,$numRep, $id_user = null, $isMobile = null)
  {
    return $this->_buy_Gala($id_shop_article,  $leGrosArray,$placesInfo,$numRep, $id_user);
  }

  public function get_last_option_id(){

    $query = $this->db->query('SELECT id_option FROM options_cart ORDER BY id_option DESC');
    $row = $query->row_array();
    return $row['id_option'];
  }

  protected function _buy_Gala($id_shop_article,$leGrosArray,$placesInfo,$numRep, $id_user) //does exactly the same as the above, just adapted to gala to replace needed things
  {
    error_reporting(0);
    //Je sais que la façon dont elle est gérée peux être optimisée, mais j'ai été sous pression du temps, donc clairement il faudrait repenser correctement un système pour check les places
    $pricesArray = $this->gala->getPriceType();
    $places = $this->gala->getPlaceType(); // Récupère les types de places, et leurs prix
    $testground = TRUE;


    $typeArray = array();
    foreach ($places[0] as $key => $value) {
        if ($value != NULL || isset($value)){
            array_push($typeArray, $value);
        }
    }
    $priceArray = array();
    foreach ($pricesArray[0] as $key => $value) {
        if ($value != NULL || isset($value)){
            array_push($priceArray, $value);

        }
    }
    $jsonDecoded = json_decode($placesInfo); //Lis les places réservées par l'utilisateur
    $typePlaces = array();
     foreach($jsonDecoded as $key => $value){ //Sépare les places et leurs types
      array_push($typePlaces, $value);
    };

    $dateGala = $this->gala->getGalaInfos()->result_array()[0]['DateRep'.$numRep];
    $pricesArray = $this->gala->getPriceType();
        $tarifs =  $this->gala->get_Tarifs()->result_array(); //Crée un tableau avec TypePlace => Prix
         for ($l=1; $l < 11; $l++) {
            if($tarifs[0]['Prix_place'.$l] !=NULL ){
                $placePrice[$tarifs[0]['Description_place'.$l]] = $tarifs[0]['Prix_place'.$l];
            }
        }
    $i = 0;
    $query = $this->gala->getGalaInfos()->result_array()[0];
    $galaTitle = $query['Titre'];
    $galaImage = $query['image'];
     foreach ($typePlaces as $value) {
      if (in_array($value, $typeArray)){

        for ($z=0; $z < count($leGrosArray)-1 ; $z++) {
          $remapper = array_merge($leGrosArray[$z]); //Refait un tableau avec tout les numéros de places en un seul array (Fixable autre part, mais je suis trop mort, donc si tu repasses pas par la, fait le quand t'as du temps)
        }
        $name_replacer = $remapper[$i]. ' au tarif '.$value.' - Places de Gala pour le '.$dateGala.' : '.$galaTitle.' - '.$numRep;

  if($testground == TRUE){
    if ($this->session->userdata('status') == "logged_in") :
      $redfamSamil = false;
      $users_in_basket = [];
      foreach ($this->get_cart() as $items) {
        array_push($users_in_basket, $items['options']['id_user']);
      }
      if (in_array($id_user, $users_in_basket)) {
        $redfamSamil = true;
      }
      $product = $this->shop_articles->get_article_basket($id_shop_article);
      $id_user_shop = $id_user;
      $redirection = $this->input->post('redirection');
      $info['array'] = $this->shop_articles->select_data_user($id_user_shop);
      $row = $info['array']->row();
      $data['page'] = 'shop';
      $quantity = 1;
      if ($this->shop_articles->count_max_per_user_basket($id_shop_article, $id_user_shop, $quantity) === FALSE) :

        $data['product'][0] = array(
          'id' => $product[0]->id_shop_article,
          'quantity' => $quantity,
          'name' => $name_replacer,
          'price' => $placePrice[$value],
          'options' => array('id_option' =>$this->get_last_option_id() +1, 'id_user' => $id_user_shop, 'Attestation_fiscale' => $product[0]->certificate, 'TVA' => $product[0]->TVA . ' %',  'Pour' => $row->firstname . ' ' . $row->lastname, 'Statut' => 'non obligatoire', 'ref' => $product[0]->ref, 'image' => $galaImage),
          'subtotal' => ($quantity) * ($placePrice[$value]),
          'date' => date("Y-m-d H:i:s")
        );
        $array = $this->recursive_data_article($id_shop_article, $row->firstname, $row->lastname, $id_user_shop, $quantity);
        if (!empty($array)) :
          foreach ($array as $arr) :
            array_push($data['product'], $arr);
          endforeach;
        endif;

        $this->insert($data['product']);
        $i++;



        $reductfami = 1;
        $nbreducfamille = 0;
        $vartest = 0;
        $id_user_test = 0;
        $id_user_test2 = 0;

        $article = $this->shop_articles->parametre('id_article_inscription')->result_array();
        $id_article = $article[0]['id_article_inscription'];
        $reduction_famille = $this->shop_articles->parametre('reduction_famille')->result_array();
        $montant = $reduction_famille[0]['reduction_famille'];

        $test = $this->shop_articles->count_bills_family($id_user_shop, $id_article);
        $test_other_fami = $this->shop_articles->count_bills_family_others($id_user_shop, $id_article);

        $totalpaniertemp = 0;

        foreach ($this->get_cart() as $items) {
          $id_user_test2 = $items['options']['id_user'];
          if ($id_user_test !== $id_user_test2 && $id_user_test2 < 1000000) :
            $id_user_test = $id_user_test2;
            $vartest++;
          endif;
          $totalpaniertemp = $totalpaniertemp + $items['price'];
        }

        //Si c'est le cas et si l'utilisateur n'est pas déjà inscrit
        if ($test == 0 && $vartest > 1) :

          $result = $this->shop_articles->verify_depend($id_shop_article);
          if (in_array($id_article, $result)) :

            $test_reduc = 0;

            foreach ($this->get_cart() as $items) {

              //Vérifie si on a l'article sur lequel s'applique la réduc
              if ($items['id'] == $id_article && $redfamSamil == false) {
                $test_reduc = 2;
                $nbreducfamille++;
              } elseif ($items['id'] == 'reductfami') {
              }
            }
            $test_reduc = $test_reduc - 1;
            if ($totalpaniertemp > $montant  && $redfamSamil == false) {
              $datamind = array(
                'id' => 'reductfami',
                'quantity' => $test_reduc,
                'name' => 'Reduction famille',
                'price' => -$montant,
                'options' => array('id_user' => $id_user_shop, 'Attestation_fiscale' => '', 'TVA' => '' . ' %', 'Pour' => '', 'Statut' => 'obligatoire', 'ref' => '', 'image' => '/Resources/Images/Logos/Icons/Reduc-Famille.png'),
                'subtotal' => ($test_reduc) * (-$montant),
                'date' => date("Y-m-d H:i:s")
              );
              $this->insert($datamind);
            };
          endif;
        //Si d'autres membres de sa famille ont sont déjà inscrits
        elseif ($test_other_fami > 0) :
          foreach ($this->get_cart() as $items) {

            //Vérifie si on a l'article sur lequel s'applique la réduc
            if ($items['id']  == $id_article && !in_array($id_user_shop, $users_in_basket)) :
              $reductfami++;
              if (($reductfami == 2) && ($totalpaniertemp > $montant)) :
                $data = array(
                  'id' => 'reductfami',
                  'quantity' => 1,
                  'name' => 'Reduction famille',
                  'price' => -$montant,
                  'options' => array('id_user' => $id_user_shop, 'Attestation_fiscale' => '', 'TVA' => '' . ' %',  'Pour' => '', 'Statut' => 'obligatoire', 'ref' => '', 'image' => '/Resources/Images/Logos/Icons/Reduc-Famille.png'),
                  'subtotal' => 1 * (-$montant),
                  'date' => date("Y-m-d H:i:s")
                );
                $this->insert($data);
              endif;
            endif;
          }
        endif;

        //Réductions automatiques dès l'ajout du 1er article
        $reducauto = 0;
        $id_reduc_auto = 0;
        foreach ($this->get_cart() as $item) :
          if ($item['options']['Statut'] == 'reducauto') :
            $reducauto++;
            $id_reduc_auto = $item['options']['id_reduction'];
          endif;
        endforeach;
        if ($this->total() > 0) :
          //Vérif des reducs auto existantes
          $array_all = $this->shop_articles->check_all_reduc_auto()->result_array();
          $array_reduction = array();
          foreach ($array_all as $all) {

            $array_reduction2 = $this->shop_articles->last_check_quantity_reduction($all['id_shop_reduction']);

            if (!empty($array_reduction2)) {
              array_push($array_reduction, $array_reduction2);
            }
          }
          if (!empty($array_reduction)) {
            foreach ($this->get_cart() as $item) :
              if ($item['options']['Statut'] == 'reducauto') :
              endif;
            endforeach;
          }

          if (!empty($array_reduction)) {
            $new = array();
            $new2 = array();
            foreach ($array_reduction as $reduc) {
              foreach ($reduc as $reduc2) {
                $new2['id_shop_reduction'] = $reduc2['id_shop_reduction'];
                $new2['id_user'] = $reduc2['id_user'];
                array_push($new, $new2);
              }
            }
            $sortArray = array();
            foreach ($new as $person) {
              foreach ($person as $key => $value) {
                if (!isset($sortArray[$key])) {
                  $sortArray[$key] = array();
                }
                $sortArray[$key][] = $value;
              }
            }
            $orderby = "id_user";
            if (!empty($sortArray)) {
              array_multisort($sortArray[$orderby], SORT_DESC, $new);
            }
            $result_eacher_user = array();
            foreach ($new as $data) {
              $id = $data['id_user'];
              if (isset($result_eacher_user[$id])) {
                $result_eacher_user[$id][] = $data;
              } else {
                $result_eacher_user[$id] = array($data);
              }
            }
            $array_fi = array();
            foreach ($result_eacher_user as $each) {
              $total = $this->shop_articles->check_total_price($each, $id_user);
              array_push($array_fi, $total);
            }
            $count_reduc_family = 0;
            foreach ($array_fi as $reduction) {
              $GetReductionsSQLString = 'SELECT `id_shop_article` FROM `liaison_shop_articles_shop_reductions` WHERE `id_shop_reduction` = ' . $reduction["id_shop_reduction"];
              $reductionsArraySamil = $this->db->query($GetReductionsSQLString)->result_array();
              $newReductionsArraySamil = array();
              foreach ($reductionsArraySamil as $redSamil) {

                array_push($newReductionsArraySamil, $redSamil['id_shop_article']);
              }
              $query = $this->shop_articles->data_user($reduction['id_user']);

              if ($query->row() != NULL) {
                $row = array();
                $row = $query->row_array();

                $usernames = $row['firstname'] . ' ' . $row['lastname'];
                //Info de la reduction
                $checkreduct = $this->shop_articles->check_shop_reduction($reduction['id_shop_reduction']);
                $redSamilInfo = $checkreduct->result_array()[0];
                if ($checkreduct->result()) :
                  $row = $checkreduct->row();
                  $test = 0;
                  //Vérif s'il y a une réduction en pourcentage
                  if ($row->percentage > 0 && $row->value > 0) :

                    $value = - ($row->percentage / 100) * $this->format_number($this->total()) - $row->value;
                    $data = array(
                      'id' => 'reducauto' . $row->id_shop_reduction,
                      'quantity' => 1,
                      'name' => $row->description,
                      'price' => $value,
                      'options' => array('id_user' => $reduction['id_user'], 'Attestation_fiscale' => '', 'TVA' => '' . ' %', 'Pour' => $usernames, 'Statut' => 'reducauto', 'ref' => 'R_AUTO_' . $row->id_shop_reduction, 'image' => '/Resources/Images/Logos/Icons/Reduc-Diverse.png', 'id_reduction' => $row->id_shop_reduction),
                      'subtotal' => 1 * $value,
                      'date' => date("Y-m-d H:i:s")
                    );
                    $this->insert($data);
                  elseif ($row->value > 0 || $row->percentage > 0) :
                    if ($reduction['id_shop_reduction'] == 2 || $reduction['id_shop_reduction'] == 3 || $reduction['id_shop_reduction'] == 4) :
                      $valueReduction = 0;
                      $pan = $this->get_cart();
                      foreach ($pan as $item) :

                        if ($item['options']['id_user'] == $reduction['id_user']  && in_array($item['id'], $newReductionsArraySamil)) :
                          $valueReduction += $item['price'];
                        endif;
                        if ($item['id'] == 'reductfami' && $count_reduc_family == 1) :
                          $count_reduc_family = 2;
                        elseif ($count_reduc_family == 0) :
                          $count_reduc_family = 1;
                        endif;
                      endforeach;
                      if ($valueReduction > 0) :
                        $data = array(
                          'id' => 'reducauto' . $row->id_shop_reduction,
                          'quantity' => 1,
                          'name' => $row->description,
                          'price' => (-$row->percentage / 100) * $valueReduction,
                          'options' => array('id_user' => $reduction['id_user'], 'Attestation_fiscale' => '', 'TVA' => '' . ' %', 'Pour' => $usernames, 'Statut' => 'reducauto', 'ref' => $redSamilInfo["code"], 'image' => '/Resources/Images/Logos/Icons/Reduc-COVID.png', 'id_reduction' => $row->id_shop_reduction),
                          'subtotal' => 1 * ((-$row->percentage / 100) * $valueReduction),
                          'date' => date("Y-m-d H:i:s")
                        );
                        $this->insert($data);
                      endif;

                    elseif ($reduction['id_shop_reduction'] == 5 || $reduction['id_shop_reduction'] == 6 || $reduction['id_shop_reduction'] == 7 || $reduction['id_shop_reduction'] == 8 || $reduction['id_shop_reduction'] == 9) :
                      $valueReduction = 0;
                      foreach ($this->get_cart() as $item) :
                        if ($item['options']['id_user'] == $reduction['id_user']   && in_array($item['id'], $newReductionsArraySamil)) :
                          $valueReduction += $item['price'];
                        endif;
                        if ($item['id'] == 'reductfami' && $count_reduc_family = 1) :
                          $count_reduc_family = 2;
                        elseif ($count_reduc_family = 0) :
                          $count_reduc_family = 1;
                        endif;
                      endforeach;
                      if ($valueReduction > 0) :
                        $data = array(
                          'id' => 'reducauto' . $row->id_shop_reduction,
                          'quantity' => 1,
                          'name' => $row->description,
                          'price' => (-$row->percentage / 100) * $valueReduction - $row->value,
                          'options' => array('id_user' => $reduction['id_user'], 'Attestation_fiscale' => '', 'TVA' => '' . ' %', 'Pour' => $usernames, 'Statut' => 'reducauto', 'ref' => $redSamilInfo["code"], 'image' => '/Resources/Images/Logos/Icons/Reduc-Diverse.png', 'id_reduction' => $row->id_shop_reduction),
                          'subtotal' =>  1 * ((-$row->percentage / 100) * $valueReduction - $row->value),
                          'date' => date("Y-m-d H:i:s")
                        );
                        $this->insert($data);
                      endif;

                    elseif ($reduction['id_shop_reduction'] == 1) :
                      $valueReduction = 0;
                      foreach ($this->get_cart() as $item) :
                        if ($item['options']['id_user'] == $reduction['id_user']  && in_array($item['id'], $newReductionsArraySamil)) :
                          $valueReduction += $item['price'];
                        endif;
                      endforeach;
                      if ($valueReduction > 0) :
                        $data = array(
                          'id' => 'reducauto' . $row->id_shop_reduction,
                          'quantity' => 1,
                          'name' => $row->description,
                          'price' => (-$row->percentage / 100) * $valueReduction,
                          'options' => array('id_user' => $reduction['id_user'], 'Attestation_fiscale' => '', 'TVA' => '' . ' %', 'Pour' => $usernames, 'Statut' => 'reducauto', 'ref' => $redSamilInfo["code"], 'image' => '/Resources/Images/Logos/Icons/Reduc-Jpo.png', 'id_reduction' => $row->id_shop_reduction),
                          'subtotal' => ((-$row->percentage / 100) * $valueReduction) * 1,
                          'date' => date("Y-m-d H:i:s")
                        );
                        $this->insert($data);
                      endif;

                    elseif ($row->value > 0) :
                      $data = array(
                        'id' => 'reducauto' . $row->id_shop_reduction,
                        'quantity' => 1,
                        'name' => $row->description,
                        'price' => -$row->value,
                        'options' => array('id_user' => $reduction['id_user'], 'Attestation_fiscale' => '', 'TVA' => '' . ' %', 'Pour' => $usernames, 'Statut' => 'reducauto', 'ref' => 'R_AUTO_' . $row->id_shop_reduction, 'image' => '/Resources/Images/Logos/Icons/Reduc-Diverse.png', 'id_reduction' => $row->id_shop_reduction),
                        'subtotal' => (-$row->value) * 1,
                        'date' => date("Y-m-d H:i:s")
                      );
                      $this->insert($data);
                    elseif ($row->percentage > 0) :
                      $data = array(
                        'id' => 'reducauto' . $row->id_shop_reduction,
                        'quantity' => 1,
                        'name' => $row->description,
                        'price' => (-$row->percentage / 100) * $this->format_number($this->total()),
                        'options' => array('id_user' => $reduction['id_user'], 'Attestation_fiscale' => '', 'TVA' => '' . ' %', 'Pour' => $usernames, 'Statut' => 'reducauto', 'ref' => 'R_AUTO_' . $row->id_shop_reduction, 'image' => '/Resources/Images/Logos/Icons/Reduc-Diverse.png', 'id_reduction' => $row->id_shop_reduction),
                        'subtotal' => 1 * ((-$row->percentage / 100) * $this->format_number($this->total())),
                        'date' => date("Y-m-d H:i:s")
                      );
                      $this->insert($data);
                    endif;
                  endif;
                endif;
              }
            }
          }

        /*REDUC JPO*/
        /* foreach ($this->get_cart() as $item):
                          if ($item['id'] == 'reductjpo'):
                              //$this->update(array('rowid' => $item['rowid'], 'quantity' => 0));
                          endif;
                        endforeach;
                              $somme = $this->total();
                              $data = array(
                                  'id' => 'reductjpo',
                                  'quantity' => 1,
                                  'name' => 'Réduction Exceptionnelle 5% (25 Juin)',
                                  'price' => (-$somme*0.05),
                                  'options' => array('id_user' => 1000000, 'Attestation_fiscale' => '', 'TVA' => '' . ' %', 'Pour' => '', 'Statut' => 'obligatoire', 'ref' => 'Reduction_5%', 'image' => '/Resources/Images/Logos/Icons/Reduc-Jpo.png')
                                  'subtotal' =>(-$somme*0.05) * 1,
                                  'date' => date("Y-m-d H:i:s")
                              );
                              $this->insert($data);

                              */
        endif;
      else :
        $flash = array(

          'limit' => '<div data-closable class="alert-box callout warning">
                      <i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Vous ne pouvez pas inscrire la même personne plusieurs fois au même cours !&nbsp;&nbsp;<a href="' . base_url('index.php/shop/basket_content') . '"><span color="#000000"><img src="' . base_url('image/panier.png') . '" width="40px" /> Aller au Panier</span></a><button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                        <span aria-hidden="true">&CircleTimes;</span>
                      </button></div>'
        );
        $this->session->set_flashdata($flash);
        if ($redirection != "Passer au paiement") {
          redirect('shop/details/' . $product[0]->url_shop_article);
        } else {
          redirect('shop/basket_content');
        };
      endif;
      $flash = array(

        'message' => '<div data-alert class="alert-box success "> Produit ajouté <a href="#" class="close">&times;</a></div>'
      );
      $this->session->set_flashdata($flash);
    else :
      redirect('');

    endif;
  }
}else{
  $a++;
}

// }
// }

}
  redirect('shop/basket_content');
//}
}
}
