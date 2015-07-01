<?php

namespace Shop\PaymentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\Request,
    Payum\Core\Model\Order,
    Payum\Core\Request\GetHumanStatus,
    Symfony\Component\HttpFoundation\JsonResponse,
    Shop\PaymentBundle\Entity\Invoice,
        Shop\PaymentBundle\Services\InvoiceService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class PaymentController extends Controller {

    private $request ;
    
    
    
    
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function listInvoicesAction(){
        $invoices = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('ShopPaymentBundle:Invoice')
                ->findBy(array(
                    'user' => $this->getUser()->getId()
                ));
        
        return $this->render('ShopPaymentBundle:Members:invoices.html.twig', array(
                    'invoices' => $invoices
        ));
    }
    
    
    
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function invoiceAction(Request $request, $id) {
        $invoice = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('ShopPaymentBundle:Invoice')
                ->find($id);
        
        if ( $invoice == null || $invoice->getUser()->getId() != $this->getUser()->getId() ){
            return $this->redirect($this->get('router')->generate('shop_payment_invoices'));
        }
        
        
        if ( $this->get("invoicing")->getRemainingPrice($invoice) <= 0 ){
            $html = $this->renderView('ShopPaymentBundle::invoice.html.twig', array(
                    'invoice' => $invoice,
                    'pdf' => true
                ));
            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                200,
                array(
                    'Content-Type'          => 'application/pdf',
                    'Content-Disposition'   => 'attachment; filename="file.pdf"'
                )
            );
        }
        
        $this->request = $request ;
        
        $translator = $this->get('translator');
        
        $paymentsAvailable = $this->container->getParameter('payments')['available'];

        $ConcatPaymentAvailable = array();
        foreach ($paymentsAvailable['payum'] as $value) {
            $ConcatPaymentAvailable[$value] = $translator->trans($value);
        }
        if ( $this->getUser()->getBalance() > 0.00 ){
            $ConcatPaymentAvailable["balance"] = $translator->trans("balance (".$this->getUser()->getBalance().")");
        }

        $form = $this->createFormBuilder()
                ->add('payments', 'choice', array(
                    'choices' => $ConcatPaymentAvailable
                ))
                ->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($request);

            $ConcatPaymentAvailableResponse = $form->getData();
            
            return $this->prepare($ConcatPaymentAvailableResponse['payments'], $invoice);
        }
        return $this->render('ShopPaymentBundle::invoice.html.twig', array(
                    'invoice' => $invoice,
                    'payments' => $form->createView()
        ));
    }

    public function doneAction(Request $request) {

        if ($request->query->has('payum_token')) {
            return $this->donePayum($request);
        }
    }

    public function prepare($paymentName, Invoice $invoice) {
        if ( $paymentName == "balance"){
            $flashbag = $this->request->getSession()->getFlashBag() ;
            switch ( $this->get('invoicing')->payWithBalance($invoice) ) {
                case InvoiceService::BALANCE_PAY_COMPLETE : 
                    $flashbag->add('success', $this->get('translator')->trans('shop.payment.pay.balance_complete'));
                    break ; 
                case InvoiceService::BALANCE_PAY_FAIL : 
                    $flashbag->add('danger', $this->get('translator')->trans('shop.payment.pay.balance_failure'));
                    break ; 
                case InvoiceService::BALANCE_PAY_PARTIAL : 
                    $flashbag->add('success', $this->get('translator')->trans('shop.payment.pay.balance_partial'));
                    break ; 
                default : break ; 
            }
            return $this->redirect($this->request->headers->get('referer'));
            
        }
        if (in_array($paymentName, $this->container->getParameter('payments')['available']['payum'])) {
            return $this->preparePayum($paymentName, $invoice);
        }
    }

    

    private function preparePayum($paymentName, Invoice $invoice) {

        $storage = $this->get('payum')->getStorage($this->container->getParameter('payum.class_order'));

        $order = $storage->create();
        $order->setNumber(uniqid());
        $order->setCurrencyCode($invoice->getCurrency());
        $order->setTotalAmount($this->get('invoicing')->getRemainingPrice($invoice) * 100);
        $order->setDescription('Description');
        //$order->setClientId('123456789ABCDEFG');
        //$order->setClientEmail('foo@example.com');
        $order->setDetails(array(
            'invoice_id' => $invoice->getId(),
            'payment_method' => $paymentName
        ));
        $storage->update($order);



        $captureToken = $this->get('payum.security.token_factory')->createCaptureToken(
                $paymentName, $order, $this->container->getParameter('payum.done_route')
        );

        return $this->forward('PayumBundle:Capture:do', array(
                    'payum_token' => $captureToken,
        ));
    }

    private function donePayum(Request $request) {

        $token = $this->get('payum.security.http_request_verifier')->verify($request);
        $paymentName = $token->getPaymentName();
        $payment = $this->get('payum')->getPayment($paymentName);

        $payment->execute($status = new GetHumanStatus($token));
        $order = $status->getFirstModel();

        $orderDetails = $order->getDetails();
        $invoice_id = $orderDetails['invoice_id'];
        $service = $paymentName;
        $date = null ; 
        
        if ($paymentName == "paypal_express") {
            $value = $orderDetails['PAYMENTREQUEST_0_AMT'];
            $txnId = $orderDetails['PAYMENTINFO_0_TRANSACTIONID'];
            //$date = $orderDetails['TIMESTAMP'] ;
        }

        $invoiceCreated = $this->get('invoicing')->addTransactionToInvoice($invoice_id, $txnId, $service, $value, $date);

        var_dump($order);
        // you have order and payment status 
        // so you can do whatever you want for example you can just print status and payment details.

        return new JsonResponse(array(
            'status' => $status->getValue(),
            'order' => array(
                'total_amount' => $order->getTotalAmount(),
                'currency_code' => $order->getCurrencyCode(),
                'details' => $orderDetails,
            ),
        ));
    }

}
