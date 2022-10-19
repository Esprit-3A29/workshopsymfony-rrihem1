<?php

namespace App\Controller;
use App\Entity\Student;
use App\Form\StudentType;
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
    public function listStudent(StudentRepository $repository){
        $students= $repository->findAll();
        return $this->render("student/listStudent.html.twig",array("tabStudent"=>$students));
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
