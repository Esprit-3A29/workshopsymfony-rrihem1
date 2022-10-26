<?php

namespace App\Controller;
use App\Entity\Student;
use App\Form\StudentType;
use App\Form\SearchStudentType;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    #[Route('/students', name: 'app_Student')]
    public function listStudent(Request $request,StudentRepository $repository)
    {
       $students= $repository->findAll();
       $formSearch= $this->createForm(SearchStudentType::class);
       $formSearch->handleRequest($request);
       $sortByMoyenne= $repository->sortByMoyenne();
       if($formSearch->isSubmitted()){
           $nce= $formSearch->get('nce')->getData();
           //var_dump($nce).die();
           $result= $repository->searchStudent($nce);
           return $this->renderForm("student/listStudent.html.twig",
               array("tabStudent"=>$result, "sortByMoyenne"=>$sortByMoyenne,"searchForm"=>$formSearch));
       }
         return $this->renderForm("student/listStudent.html.twig",
           array("tabStudent"=>$students,"searchForm"=>$formSearch, "sortByMoyenne"=>$sortByMoyenne,));
    }


    #[route('/addStudent',name:'add_student')]
public function addStudent(ManagerRegistry $doctrine,Request $request ,StudentRepository $repository)
{
    $student =new Student;
    $form =$this->createForm(StudentType::class,$student);
    $form->handleRequest($request);
    if($form->isSubmitted())
    {
        $repository->add($student,true);
        return $this->redirectToRoute("app_Student");
    }
    return $this->renderForm("student/add.html.twig",array("formStudent"=>$form));
}
}
