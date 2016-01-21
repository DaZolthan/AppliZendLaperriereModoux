<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Movie\Model;

 use Zend\Db\TableGateway\TableGateway;

 class MovieTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

     public function fetchAll()
     {
         $resultSet = $this->tableGateway->select();
         return $resultSet;
     }
     
     public function findMovieByIdUser($userId)
     {
         $resultSet = $this->tableGateway->select(['users_id' => $userId]);
         if(!$resultSet) {
             throw new \Exception("Could not find movie from $id");
         }
         return $resultSet;
     }

     public function getMovie($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     public function saveMovie(Movie $movie)
     {
         $data = array(
             'director' => $movie->director,
             'title'  => $movie->title,
             'date' => $movie->date
         );

         $id = (int) $movie->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getMovie($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('Movie id does not exist');
             }
         }
     }

     public function deleteMovie($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
 }
