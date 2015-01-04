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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @Route("/bills", name="bills")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexBillAction()
    {
        $bill  = new Bill();
        $form  = $this->createForm('bill', $bill);
        $user  = $this->get('security.token_storage')
                      ->getToken()
                      ->getUser();
        $em    = $this->getDoctrine()
                      ->getManager();
        $bills = $em->getRepository('AppBundle:Bill')
                    ->findAllUnPaidByUser($user->getID());

        return $this->render(
            'AppBundle:Bill:bill.html.twig',
            array(
                'form'  => $form->createView(),
                'bills' => $bills,
            )
        );
    }

    /**
     * Create a new Bill
     *
     * @Route("/bills", name="create_bill")
     * @Method("POST")
     * @param Request $request
     *
     * @return Response
     */
    public function createBillAction(Request $request)
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

        $bills = $em->getRepository('AppBundle:Bill')
                    ->findAllUnPaidByUser($user->getID());

        return $this->render(
            'AppBundle:Bill:bill.html.twig',
            array(
                'form'  => $form->createView(),
                'bills' => $bills,
            )
        );

    }

    /**
     * Edit an existing Bill
     *
     * @Route("/bills/{id}", name="edit_bill")
     * @Method({"GET", "PUT"})
     * @param Bill    $bill
     * @param Request $request
     * @ParamConverter("bill", class="AppBundle:Bill")
     *
     * @return Response
     */
    public function editBillAction(Bill $bill, Request $request)
    {
        $form = $this->createForm(
            'bill',
            $bill,
            ['method' => 'PUT', 'action' => $this->generateUrl('edit_bill', ['id' => $bill->getId()])]
        );
        $user = $this->get('security.token_storage')
                     ->getToken()
                     ->getUser();
        $em   = $this->getDoctrine()
                     ->getManager();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $bill->setPaid(false);
            $em->persist($bill);
            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Bill Updated');
        }

        $bills = $em->getRepository('AppBundle:Bill')
                    ->findAllUnPaidByUser($user->getID());

        return $this->render(
            'AppBundle:Bill:bill.html.twig',
            array(
                'form'  => $form->createView(),
                'bills' => $bills,
            )
        );

    }
}
