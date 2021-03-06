<?php

require_once('../model/TareasModel.php');
require_once('Api.php');
/**
 *
 */
class TareasApiController extends Api
{
  protected $model;

  function __construct()
  {
      parent::__construct();
      $this->model = new TareasModel();
//      $this->palabrasProhibidasModel = new PalabrasProhibidasModel();
  }

  public function getTareas($url_params = [])
  {
      $tareas = $this->model->getTareas();
      $tareas_editadas = [];
      foreach ($tareas as $tarea) {
        $tarea['completado'] =  $tarea['completado'] ? true : false;
        array_push($tareas_editadas,$tarea);
      }

      $response = new stdClass();
      $response->tareas = $tareas_editadas;
      $response->status = 200;
      return $this->json_response($response, 200);
  }

  public function getTarea($url_params = [])
  {
      $id_tarea = $url_params[":id"];
      $tarea = $this->model->getTarea($id_tarea);
      if($tarea)
        return $this->json_response($tarea, 200);
      else
        return $this->json_response(false, 404);
  }

  public function getDescripcion($url_params = [])
  {
    $id_tarea = $url_params[":id"];
    $tarea = $this->model->getTarea($id_tarea);
    if($tarea)
      return $this->json_response($tarea["descripcion"], 200);
    else
      return $this->json_response(false, 404);
  }

  public function deleteTareas($url_params = [])
  {
      $id_tarea = $url_params[":id"];
      $tarea = $this->model->getTarea($id_tarea);
      if($tarea)
      {
        $this->model->borrarTarea($id_tarea);
        return $this->json_response("Borrado exitoso.", 200);
      }
      else
        return $this->json_response(false, 404);
  }

  public function createTareas($url_params = []) {
    $body = json_decode($this->raw_data);
    $titulo = $body->titulo;
    $descripcion = $body->descripcion;
    $completada = $body->completada;
    $tarea = $this->model->guardarTarea($titulo, $descripcion, $completada);
    $tarea['completado'] = $tarea['completado'] ? true : false;
    $response = new stdClass();
    $response->tareas = [$tarea];
    $response->status = 200;
    return $this->json_response($response, 200);
  }

  public function editTarea($url_params = []) {
    $body = json_decode($this->raw_data);
    $id = $url_params[":id"];
    $titulo = $body->titulo;
    $descripcion = $body->descripcion;
    $completada = $body->completada;
    $tarea = $this->model->modificarTarea($id, $titulo, $descripcion, $completada);
    return $this->json_response($tarea, 200);
  }

  public function updateCompletado($url_params = []) {
    $id = $url_params[":id"];
    $this->model->finalizarTarea($id);
    return $this->json_response("Finalizado exitoso.", 200);
  }
}

 ?>
