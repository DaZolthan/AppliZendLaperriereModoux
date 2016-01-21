<?php

namespace Book\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Book\Model\Book;
use Book\Form\BookForm;

class BookController extends AbstractActionController {

    protected $bookTable;
    protected $authservice;

    public function indexAction() {
        $user = $this->getAuthService()->getStorage()->read();
        return new ViewModel(array(
            'books' => $this->getBookTable()->findBookByIdUser($user->id),
        ));
    }

    public function addAction() {
        $form = new BookForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $book = new Book();
            $form->setInputFilter($book->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $book->exchangeArray($form->getData());
                $this->getBookTable()->saveBook($book);

                // Redirect to list of books
                return $this->redirect()->toRoute('book');
            }
        }
        return array('form' => $form);
    }

    public function editAction() {

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('book', array(
                        'action' => 'add'
            ));
        }

        // Get the Book with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $book = $this->getBookTable()->getBook($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('book', array(
                        'action' => 'index'
            ));
        }

        $form = new BookForm();
        $form->bind($book);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($book->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getBookTable()->saveBook($book);

                // Redirect to list of books
                return $this->redirect()->toRoute('book');
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
            return $this->redirect()->toRoute('book');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getBookTable()->deleteBook($id);
            }

            // Redirect to list of books
            return $this->redirect()->toRoute('book');
        }

        return array(
            'id' => $id,
            'book' => $this->getBookTable()->getBook($id)
        );
    }

    public function getBookTable() {
        if (!$this->bookTable) {
            $sm = $this->getServiceLocator();
            $this->bookTable = $sm->get('Book\Model\BookTable');
        }
        return $this->bookTable;
    }
    
    public function getAuthService() {
        if (!$this->authservice) {
            $this->authservice = $this->getServiceLocator()
                    ->get('AuthService');
        }

        return $this->authservice;
    }

}
