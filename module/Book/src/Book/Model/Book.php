<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 namespace Book\Model;

 // Add these import statements
 use Zend\InputFilter\InputFilter;
 use Zend\InputFilter\InputFilterAwareInterface;
 use Zend\InputFilter\InputFilterInterface;

 class Book implements InputFilterAwareInterface
 {
     public $id;
     public $author;
     public $title;
     public $type;
     protected $inputFilter;                       // <-- Add this variable

     public function exchangeArray($data)
     {
         $this->id     = (isset($data['id']))     ? $data['id']     : null;
         $this->author = (isset($data['author'])) ? $data['author'] : null;
         $this->title  = (isset($data['title']))  ? $data['title']  : null;
         $this->type   = (isset($data['type']))   ? $data['type']   : null;
     }

      // Add the following method:
     public function getArrayCopy()
     {
         return get_object_vars($this);
     }

     // Add content to these methods:
     public function setInputFilter(InputFilterInterface $inputFilter)
     {
         throw new \Exception("Not used");
     }

     public function getInputFilter()
     {
         if (!$this->inputFilter) {
             $inputFilter = new InputFilter();

             $inputFilter->add(array(
                 'name'     => 'id',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'Int'),
                 ),
             ));

             $inputFilter->add(array(
                 'name'     => 'author',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                 ),
                 'validators' => array(
                     array(
                         'name'    => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'min'      => 1,
                             'max'      => 100,
                         ),
                     ),
                 ),
             ));

             $inputFilter->add(array(
                 'name'     => 'title',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                 ),
                 'validators' => array(
                     array(
                         'name'    => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'min'      => 1,
                             'max'      => 100,
                         ),
                     ),
                 ),
             ));
             
             $inputFilter->add(array(
                 'name'     => 'type',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                 ),
                 'validators' => array(
                     array(
                         'name'    => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'min'      => 1,
                             'max'      => 100,
                         ),
                     ),
                 ),
             ));

             $this->inputFilter = $inputFilter;
         }

         return $this->inputFilter;
     }
 }

