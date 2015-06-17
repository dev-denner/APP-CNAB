<?php

/**
 * Description of MY_Model
 *
 * @author Denners
 */
class MY_Model extends CI_Model {

  private $class = NULL;
  protected $dados = NULL;

  public function __construct() {
    parent::__construct();
    $this->getClass();
  }

  /* SAVE */

  public function save($dados = array(), $criterio = NULL) {

    try {
      $class = $this->getClass();

      $resposta = FALSE;

      if (is_array($dados) && !empty($dados)):
        if (!is_null($criterio) && !empty($criterio)):
          $resposta = $this->atualizar($dados, $criterio);
        else:
          $this->db->insert($class::TABELA, $dados);
          $resposta = TRUE;
        endif;
      else:
        throw new Exception('Erro de cadastro!');
      endif;
      return $resposta;
    } catch (Exception $exc) {
      return $exc->getMessage();
    }
  }

  /*   * ********************************** */
  /* UPDATE */

  private function atualizar($dados = array(), $criterio = NULL) {

    try {
      $class = $this->getClass();

      $erro = FALSE;

      switch (self::getTipo($criterio)) {
        case 'integer':
          $this->db->where($class::ID, $criterio);
          break;
        case 'array':
          $this->db->where($criterio);
          break;
        case 'string':
          $this->db->where($class::NOME, $criterio);
          break;
        default :
          $erro = TRUE;
          break;
      }

      if (!$erro) {
        $this->db->update($class::TABELA, $dados);
        return TRUE;
      } else {
        throw new Exception('Falha ao atualizar dado.');
      }
    } catch (Exception $exc) {
      return $exc->getMessage();
    }
  }

  /*   * ********************************** */
  /* DELETE */

  public function deletar($ID) {
    try {
      $class = $this->getClass();
      $this->db->where($class::ID, $ID);
      $resposta = $this->db->delete($class::TABELA);
      if (!$resposta) {
        throw new Exception('Falha ao excluir dado.');
      }
      return $resposta;
    } catch (Exception $exc) {
      return $exc->getMessage();
    }
  }

  /*   * ********************************** */
  /* DESATIVAR */

  public function desativar($dados = array(), $criterio = NULL) {
    try {
      $class = $this->getClass();
      $this->db->where($class::ID, $criterio);
      $this->db->update($class::TABELA, $dados);
      return TRUE;
    } catch (Exception $exc) {
      return $exc->getMessage();
    }
  }

  /*   * ********************************** */
  /* GETS */

  public static function getTipo($variavel = NULL) {
    $resposta = NULL;

    if (is_numeric($variavel)):
      $resposta = 'integer';
    else:
      $resposta = gettype($variavel);
    endif;
    return $resposta;
  }

  /*   * ********************************** */

  public function get($criterio = NULL) {
    try {

      $return = FALSE;
      switch (self::getTipo($criterio)) {
        case 'array':
          $this->dados = $this->getByCriterio($criterio);
          break;
        case 'integer':
          $this->dados = $this->getByID($criterio);
          break;
        case 'string':
          $this->dados = $this->getByString($criterio);
          break;
      }

      if (!empty($this->dados)) {
        $return = $this->dados;
        unset($this->dados);
      }

      return $return;
    } catch (Exception $exc) {
      return $exc->getMessage();
    }
  }

  /*   * ********************************** */

  public function getAll($page = NULL, $paginacao = NULL) {

    try {
      $class = $this->getClass();

      $return = NULL;

      if (!is_null($page) && !is_null($paginacao)) {
        $this->db->order_by($class::ID, "ASC");
        $query = $this->db->get($class::TABELA, $page, $paginacao);
      } else {
        $query = $this->db->get($class::TABELA);
      }

      $return = $query->result_array();

      if (!is_null($return)) {
        return $return;
      } else {
        throw new Exception('Não há registros.');
      }
    } catch (Exception $exc) {
      return $exc->getMessage();
    }
  }

  /*   * ********************************** */

  private function getByCriterio($criterio = NULL) {

    try {

      $class = $this->getClass();

      $this->db->where($criterio);
      $this->db->order_by($class::ID, "ASC");
      $query = $this->db->get($class::TABELA);
      if ($query) {
        return $query->result_array();
      } else {
        throw new Exception('Não há registros.');
      }
    } catch (Exception $exc) {
      return $exc->getMessage();
    }
  }

  /*   * ********************************** */

  private function getByString($criterio = NULL) {

    try {

      $class = $this->getClass();

      $this->db->where($criterio);
      $this->db->order_by($class::ID, "ASC");
      $query = $this->db->get($class::TABELA);
      if ($query) {
        return $query->result_array();
      } else {
        throw new Exception('Não há registros.');
      }
    } catch (Exception $exc) {
      return $exc->getMessage();
    }
  }

  /*   * ********************************** */

  private function getByID($ID) {

    try {

      $class = $this->getClass();

      $this->db->where($class::ID, $ID);
      $this->db->order_by($class::ID, "ASC");
      $query = $this->db->get($class::TABELA);
      if (!empty($query)) {
        return $query->result_array();
      } else {
        throw new Exception('Não há registros.');
      }
    } catch (Exception $exc) {
      return $exc->getMessage();
    }
  }

  /*   * ********************************** */

  private function getClass() {
    if (is_null($this->class)) {
      $this->class = get_class($this);
    }
    return $this->class;
  }

  /*   * ********************************** */

  public function autoincrement($ID = NULL) {
    try {

      $class = $this->getClass();

      if (is_null($ID)) {
        $ID = $class::ID;
      }

      $query = $this->db->query('SELECT NVL(MAX(' . $ID . ' ), 0)+1 ID FROM ' . $class::TABELA);

      if (!empty($query)) {
        return $query->result_array()[0]['ID'];
      } else {
        throw new Exception('Erro ao processar autoincrement.');
      }
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

  public function getSequence() {
    try {

      $query = $this->db->query('SELECT SEQUENCE1.NEXTVAL FROM DUAL');

      if (!empty($query)) {
        return $query->result_array()[0]['NEXTVAL'];
      } else {
        throw new Exception('Erro ao processar autoincrement.');
      }
    } catch (Exception $exc) {
      return $exc->getMessage();
    }
  }

}
