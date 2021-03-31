<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Autorisation;
use App\Entity\Utilisateur;
use App\Entity\Document;
use App\Entity\Acces;



class MonController extends AbstractController
{
    /**
     * @Route("/mon", name="mon")
     */
    public function index(Request $request, EntityManagerInterface $manager,SessionInterface $session): Response
    {
        $vs=$session->get('userid');

            if($vs == 0){
                return $this->render('mon/index.html.twig', [
                    'controller_name' => 'page de Login',
                ]);
            }
            else{
                $utilisateur = $manager ->getRepository(utilisateur::class)->findOneBy(array('id' => $vs));
                return $this->render('mon/login_result.html.twig', [
                    'controller_name' => 'MonController',
                    'utilisateur' => $utilisateur
    
                ]);

                if($nom == "admin"){
                    $status = "administrateur";
                }
                else{
                    $status = "utilisateur";
                }
            }
        
    }

    /**
     * @Route("/creation", name="creation")
     */
    public function utilisateur(Request $request, EntityManagerInterface $manager,SessionInterface $session): Response
    {

        return $this->render('mon/utilisateurs.html.twig', [
            'controller_name' => 'MonController',
        ]);
    }


     /**
     * @Route("/login", name="/login")
     */
    public function login(Request $request, EntityManagerInterface $manager,SessionInterface $session): Response
    {
        //Recupere les informations de inscription
        $Login= $request->request->get('email');
        $Pass= $request->request->get('password');

        //Recherche l'utilisateur dans la base de donnée
        $utilisateur = $manager ->getRepository(utilisateur::class)->findOneBy(array('Email' => $Login,'Password' => $Pass));
        $Pass2=$utilisateur->getPassword();
        $Verif=password_verify($Pass,$Pass2);

        /*//Test pour voir si l'utilisateur est admin
        if($utilisateur == admin){
            $status = "Administrateur";
        }
        else{
            $status = "Utilisateur";
        }*/

        //Verifie le mot de passe
        if($Verif != TRUE){
            $val =$utilisateur->getid();
            $session->set('userid',$val);
            return $this->render('mon/login_result.html.twig', [
                'controller_name' => 'MonController',
                'utilisateur' => $utilisateur

            ]);
        }
        else{
            $session->set('userid',0);
            return $this->redirectToRoute('mon');

        }
    }




    /**
     * @Route("/cUser", name="cUser")
     */
    public function creation(Request $request, EntityManagerInterface $manager,SessionInterface $session): Response
    {
        //Recuperation des données saisies
        $recupNom=$request->request->get('nom');
        $recupPrenom=$request->request->get('prenom');
        $recupEmail=$request->request->get('email');
        $recupPassword=$request->request->get('password');
        $PassCrypt=password_hash($recupPassword,PASSWORD_DEFAULT);


        //Creation d'un objet Etudiant
        $User = new Utilisateur();

        //Insertion de la valeur dans l'objet
		$User->setNom($recupNom);
		$User->setPrenom($recupPrenom);
        $User->setEmail($recupEmail);
        $User->setPassword($PassCrypt);

        //Utilisation de la percistance pour stocker l'objet
        $manager->persist($User);
		$manager->flush();

        //Valeur de retour
        return $this->redirectToRoute('listeUser');
                
            
        
    }
    
    /**
     * @Route("/listeUser", name="listeUser")
     */
    public function liste(Request $request, EntityManagerInterface $manager,SessionInterface $session): Response
    {
            $userid=$session->get('userid');
            if ($userid>0){
                $listUser=$manager->getRepository(Utilisateur::class)->findall();

            return $this->render('mon/listeUser.html.twig', [
                'listUser' => $listUser
            ]);
            }
            else{
                return new Response("erreur de connexion");
            }
    }

            
    /**
     * @Route("/fichier", name="fichier")
     */
    public function fichier(Request $request, EntityManagerInterface $manager,SessionInterface $session): Response
    {
        return $this->render('mon/ajoutFichier.html.twig', [
        ]);
    }

    /**
     * @Route("/ajoutfichier", name="ajoutfichier")
     */
    public function ajoutfichier(Request $request, EntityManagerInterface $manager,SessionInterface $session): Response
    {
        //On récupère les informations entrées
        $chemin='C:\wamp64\www\M4207bis\public';
        $nom=$_FILES['fichier']['name'];
        $nomtmp=$_FILES['fichier']['tmp_name'];
        $dest=$chemin.'\\'.basename($_FILES['fichier']['name']);
        $resultat=move_uploaded_file($_FILES['fichier']['tmp_name'],$dest);

        //création document
        $Doc = new Document();
        $Doc->setChemin($dest);
        $date = new \DateTime('NOW');
        $Doc->setDate($date);
        $Doc->setActif(1);

        //Utilisation de la percistance pour stocker l'objet
        $manager->persist($Doc);
        $manager->flush();

        return $this->redirectToRoute('listeFichier');
    }


    /**
     * @Route("/listeFichier", name="listeFichier")
     */
    public function listeFichier(Request $request, EntityManagerInterface $manager,SessionInterface $session): Response
    {
            $userid=$session->get('userid');
            if ($userid>0){
                $listFichier=$manager->getRepository(Document::class)->findall();

            return $this->render('mon/listeFichier.html.twig', [
                'listFichier' => $listFichier
            ]);
            }
            else{
                return new Response("erreur de connexion");
            }
    }
    
    /**
     * @Route("/deconnexion", name="deconnexion")
     */
    public function deconnexion(Request $request, EntityManagerInterface $manager,SessionInterface $session): Response
    {
            $session->clear();
            return $this->redirectToRoute('mon');
                    
    }

}
