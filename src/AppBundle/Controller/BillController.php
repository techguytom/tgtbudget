<?php
/**
 * BillController.php
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
use AppBundle\Entity\Bill;

/**
 * Handles Bill forms and views
 *
 * @package AppBundle\Controller
 * @subpackage
 * @author  Tom Jenkins <tom@techguytom.com>
 */
class BillController extends Controller
{
    /**
     * Bill index page
     *
     * @param Request $request
     * @Route("/bills", name="bills")
     * @Method({"GET", "POST"})
     *
     * @return Response
     */
    public function billAction(Request $request)
    {
        $bill = new Bill();
        $form = $this->createForm('bill', $bill);
        $user = $this->get('security.token_storage')
                     ->getToken()
                     ->getUser();
        $em   = $this->getDoctrine()
                     ->getManager();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $bill->setUser($user);
            $bill->setPaid(false);
            $em->persist($bill);
            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Bill Saved');
            $bill = new Bill();
            $form = $this->createForm('bill', $bill);
        }

        $billRepository = $em->getRepository('AppBundle:Bill');
        $bills          = $billRepository->findAllUnPaidByUser($user->getID());

        return $this->render(
            'AppBundle:Bill:bill.html.twig',
            array(
                'form'  => $form->createView(),
                'bills' => $bills,
            )
        );
    }
}
