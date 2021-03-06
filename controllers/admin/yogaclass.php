<?php

require_once 'models/course.php'; 
require_once 'models/teacher.php'; 
require_once 'models/yogaclass.php';
require_once 'libraries/utils.php';

//Show all yogaclass planned
function index(){
    $yogaclass = findAllClasses();
    renderPageAdmin('yogaclass/list', compact('yogaclass'));
}

//Create a yogaclass
function create(){

    //creation yogaclass form processing
    if(!empty($_POST)){

        //if required inputs are empty, we display error message
        if(empty($_POST['yogaType'])
            || empty($_POST['teacher'])
            || empty($_POST['date'])
            || empty($_POST['time'])
            || empty($_POST['seat'])
            || empty($_POST['price'])){
                addFlashMsg('error', 'Champs obligatoires non remplis !');
                redirectBack();
            };

        //checking date format input and time format input: if string given is not a date format, display error message
        $dateTimeYoga = date_create_from_format("Y-m-d H:i", $_POST['date'].$_POST['time']);
        if(!$dateTimeYoga){
            addFlashMsg('error', 'Vous n\'avez pas rentré une date ou un horaire valide !');
            redirectBack();
        }
        
        //checking date input: if given date is in the past, display error message
        if($_POST['date'] < date('Y-m-d')){
            addFlashMsg('error', 'Veuillez choisir une date dans le futur !');
            redirectBack();
        }

        //checking time input: if given time is in the past, display error message
        if(strtotime($_POST['date'].$_POST['time']) < time()){
            addFlashMsg('error', 'Veuillez choisir un horaire dans le futur !');
            redirectBack();
        }

        //checking if number of seats or price are integers: if not, display error message
        if(!is_integer(intval($_POST['seat'])) || !is_integer(intval($_POST['price']))){
            addFlashMsg('error', 'Veuillez choisir un nombre !'); 
            redirectBack();
        }

        //if $_POST is not empty and correct, we proceed to insert yogaclass into database
        $result = insertNewClass(intval($_POST['yogaType']), 
                        intval($_POST['teacher']), 
                        $_POST['date'], 
                        $_POST['time'], 
                        intval($_POST['seat']), 
                        intval($_POST['price']));

        //if insertion is successful, display success message, else error message
        if($result){
            addFlashMsg('success', 'La séance a été crée');
            redirect('index');
        }else{
            addFlashMsg('error', 'La création a échoué');
            redirect('index');
        }         
    }
    else{
        $courses = findAllCourses();
        $teachers = findAllTeachers();
    
        renderPageAdmin('yogaclass/new', compact('courses', 'teachers'));
    }   
}

//Update a yogaclass
function update($id){

    //update yogaclass form processing
    if(!empty($_POST)){

        //if required inputs are empty, we display error message
        if(empty($_POST['yogaType'])
            || empty($_POST['teacher'])
            || empty($_POST['date'])
            || empty($_POST['time'])
            || empty($_POST['seat'])
            || empty($_POST['price'])){
                addFlashMsg('error', 'Champs obligatoires non remplis !');
                redirectBack();
        }

        //checking date format input and time format input: if string given is not a date format, display error message
        $dateTimeYoga = date_create_from_format("Y-m-d H:i", $_POST['date'].$_POST['time']);
        if(!$dateTimeYoga){
            addFlashMsg('error', 'Vous n\'avez pas rentré une date ou un horaire valide !');
            redirectBack();
        }
        
        //checking date input: if given date is in the past, display error message
        if($_POST['date'] < date('Y-m-d')){
            addFlashMsg('error', 'Veuillez choisir une date dans le futur !');
            redirectBack();
        }

        //checking time input: if given time is in the past, display error message
        if(strtotime($_POST['date'].$_POST['time']) < time()){
            addFlashMsg('error', 'Veuillez choisir un horaire dans le futur !');
            redirectBack();
        }

        //checking if number of seats or price are integers: if not, display error message
        if(!is_integer(intval($_POST['seat'])) || !is_integer(intval($_POST['price']))){
            addFlashMsg('error', 'Veuillez choisir un nombre !'); 
            redirectBack();
        }

        //if $_POST is not empty and correct, we proceed to insert yogaclass into database
        $result = updateClass($id, 
                    intval($_POST['yogaType']), 
                    intval($_POST['teacher']), 
                    $_POST['date'], 
                    $_POST['time'], 
                    intval($_POST['seat']), 
                    intval($_POST['price']));
        
        //if insertion is successful, display success message, else error message
        if($result){
            addFlashMsg('success', 'La séance a bien été mise à jour'); 
            redirect('http://localhost/yogaStudio/yogaclass');
             
        }else{
            addFlashMsg('error', 'une erreur est survenue');
            redirect('http://localhost/yogaStudio/yogaclass');
        } 
    }
    //show template with yogaclass info
    else{

        //we verify if $id is numeric, else we throw exception
        if(is_numeric($id)){
            $data = findClass($id);
            if(!$data){
                addFlashMsg('error', 'Aucune séance trouvée');
                redirect('http://localhost/yogaStudio/yogaclass');
            }
            else{
                $courses = findAllCourses();
                $teachers = findAllTeachers();
                renderPageAdmin('yogaclass/edit', compact('data', 'courses', 'teachers'));
            }
        }
        else{
            throw new Exception("argument incorrect", 3);
        }
    }
}

//Delete yogaclass process
function deleteClass($classId){

    if(is_numeric($classId)){

        //first we verify that the yogaclass exists
        $data = findClass($classId);
        //if not we display an error message
        if(!$data){
            addFlashMsg('error', 'Aucune séance trouvée');
            redirect('http://localhost/yogaStudio/yogaclass');
        }
        // else we proceed to delete the yogaclass from database
        else{
            $result = delete($classId, 'Yogaclass');
            //if delete is successful, display success message, else error message
            if($result){  
                addFlashMsg('success', 'Séance supprimée!');
                redirectBack();               
            }else{
                addFlashMsg('error', 'Erreur survenue');
                redirectBack();
            }
        }
    }else{
        throw new Exception("argument incorrect", 3);
    }
}
