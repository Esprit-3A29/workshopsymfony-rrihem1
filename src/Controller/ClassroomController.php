<?php

namespace App\Controller;
use App\Entity\Classroom;
use App\Form\ClassroomType;
use App\Repository\ClassroomRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ClassroomController extends AbstractController
{
#[route('/classroom',name:'app_classroom')]
public function index():Response
{
    return $this->render('classroom/index.html.twig',['controller_name'=>'ClassroomController',]);

}

#[route('/listclassroom',name:'list_classroom')]
public function listClassroom(ClassroomRepository $repository)
{
    $classroom=$repository->findAll();
    return $this->render("classroom/list.html.twig",array("tab_class"=>$classroom));

}
#[route('/addform',name:'add_classroom')]
public function addClassroom(ManagerRegistry $doctrine,Request $request)
{
    $classroom =new Classroom;
    $form =$this->createForm(ClassroomType::class,$classroom);
    $form->handleRequest($request);
    if($form->isSubmitted())
    {
        $em=$doctrine->getManager();
        $em->persist($classroom);
        $em->flush();
        return $this->redirectToRoute("list_classroom");
    }
    return $this->renderForm("classroom/add.html.twig",array("formClassroom"=>$form));
}

#[Route('/updateForm/{id}', name: 'update')]
public function  updateForm($id,ClassroomRepository $repository,ManagerRegistry $doctrine,Request $request)
{
    $classroom= $repository->find($id);
    $form= $this->createForm(ClassroomType::class,$classroom);
    $form->handleRequest($request) ;
    if ($form->isSubmitted()){
        $em= $doctrine->getManager();
        $em->flush();
        return  $this->redirectToRoute("list_classroom");
    }
    return $this->renderForm("classroom/update.html.twig",array("formClassroom"=>$form));
}

#[Route('/removeForm/{id}', name: 'remove')]

public function removeClassroom(ManagerRegistry $doctrine,$id,ClassroomRepository $repository)
{
    $classroom= $repository->find($id);
    $em = $doctrine->getManager();
    $em->remove($classroom);
    $em->flush();
    return  $this->redirectToRoute("list_classroom");
}
}