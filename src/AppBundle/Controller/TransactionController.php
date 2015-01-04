<?php
/**
 * TransactionController.php
 *
 * @package AppBundle\Controller
 * @subpackage
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Handle Transaction page
 *
 * @package AppBundle\Controller
 * @subpackage
 * @author  Tom Jenkins <techguytom@nerdery.com>
 */
class TransactionController extends Controller
{
    /**
     * Handle page view
     *
     * @Method("GET")
     * @Route("/transactions", name="transaction")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $user = $this->get('security.token_storage')
                     ->getToken()
                     ->getUser();
        $em   = $this->getDoctrine()
                     ->getManager();

        $transactions = $em->getRepository('AppBundle:Transaction')
                           ->findBy(['user' => $user->getID()], ['date' => 'DESC']);

        return $this->render(
            'AppBundle:Transaction:transaction.html.twig',
            array(
                'transactions' => $transactions,
            )
        );
    }
}
