<?php

namespace Movie\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Movie\Model\Movie;
use Movie\Form\MovieForm;

class MovieController extends AbstractActionController {

    protected $movieTable;
    protected $authservice;

    public function indexAction() {
        $user = $this->getAuthService()->getStorage()->read();
        return new ViewModel(array(
            'movies' => $this->getMovieTable()->findMovieByIdUser($user->id),
        ));
    }

    public function addAction() {
        $form = new MovieForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $movie = new Movie();
            $form->setInputFilter($movie->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $movie->exchangeArray($form->getData());
                $this->getMovieTable()->saveMovie($movie);

                // Redirect to list of movies
                return $this->redirect()->toRoute('movie');
            }
        }
        return array('form' => $form);
    }

    public function editAction() {

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('movie', array(
                        'action' => 'add'
            ));
        }

        // Get the Movie with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $movie = $this->getMovieTable()->getMovie($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('movie', array(
                        'action' => 'index'
            ));
        }

        $form = new MovieForm();
        $form->bind($movie);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($movie->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getMovieTable()->saveMovie($movie);

                // Redirect to list of movies
                return $this->redirect()->toRoute('movie');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('movie');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getMovieTable()->deleteMovie($id);
            }

            // Redirect to list of movies
            return $this->redirect()->toRoute('movie');
        }

        return array(
            'id' => $id,
            'movie' => $this->getMovieTable()->getMovie($id)
        );
    }

    public function getMovieTable() {
        if (!$this->movieTable) {
            $sm = $this->getServiceLocator();
            $this->movieTable = $sm->get('Movie\Model\MovieTable');
        }
        return $this->movieTable;
    }
    
    public function getAuthService() {
        if (!$this->authservice) {
            $this->authservice = $this->getServiceLocator()
                    ->get('AuthService');
        }

        return $this->authservice;
    }

}
