<?php
/**
 * CategoryController.php
 *
 * @package AppBundle\Controller
 * @subpackage
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Category;

/**
 * Handles the views and forms for Category pages
 *
 * @package AppBundle\Controller
 * @subpackage
 * @author  Tom Jenkins <tom@techguytom.com>
 */
class CategoryController extends Controller
{

    /**
     * Account Types Settings Page
     *
     * @param Request $request
     * @Route("/settings/categories", name="categories")
     * @Method({"GET", "POST"})
     *
     * @return Response
     */
    public function categoryAction(Request $request)
    {
        $category = new Category();
        $form     = $this->createForm('category', $category);
        $user     = $this->get('security.token_storage')
                         ->getToken()
                         ->getUser();
        $em       = $this->getDoctrine()
                         ->getManager();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $category->setUser($user);
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Category Added');
            $category = new Category();
            $form     = $this->createForm('category', $category);
        }

        $categories = $em->getRepository('AppBundle:Category')
                         ->findByUser($user);

        return $this->render(
            'AppBundle:Category:category.html.twig',
            array(
                'form'       => $form->createView(),
                'categories' => $categories,
            )
        );
    }
}
