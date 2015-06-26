<?php

namespace Services\AllopassBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ServicesAllopassRestController extends Controller {

    public function returnAllopassAction() {
        if (isset($_GET["data"])) {
            if (!isset($_GET["action"])) {
                return $this->renderRedirectView();
            }
        }
    }

    public function callbackAllopassAction() {
        if (isset($_GET["data"])) {
            if (isset($_GET["action"])) {
                /* On traite la callback  */
                return $this->getCallbackResponse();
            }
        }
    }

    private function getUsefullParamaters(&$id, &$invoice, &$renting) {
        $content = explode(";", $_GET["data"]);

        if (count($content) != 2) {
            echo "BAD FORMAT";
            return false;
        }

        $id = $content[0];
        $invoice = $content[1];

        $renting = $this->getDoctrine()
                ->getRepository('ServicesCoreBundle:Renting')
                ->find($id);

        if ($renting == null) {
            echo "NO RENTING";
            return false;
        }

        return true;
    }

    private function getCallbackResponse() {

        $id = $invoice = $renting = 0;

        if ($this->getUsefullParamaters(&$id, &$invoice, &$renting) == false) {
            die();
        }

        if ($renting->getService()->getName() == "whcms_allopass_commission") {
            $url = $this->getCompleteAddress($renting) . "/modules/gateways/callback/allopass.php";
        } elseif ($renting->getService()->getName() == "hostbill_allopass_commission") {
            $url = $this->getCallbackAddress($renting) . "&purchase=" . $code_fichier;
        } else {
            echo "BAD RENTING";
            die();
        }


        return new RedirectResponse(
                $url . http_build_query($_GET), 307, array(
            "GET" => $_GET
                )
        );
    }

    private function renderRedirectView() {

        $id = $invoice = $renting = 0;

        if ($this->getUsefullParamaters(&$id, &$invoice, &$renting) == false) {
            die();
        }

        if ($renting->getService()->getName() == "whcms_allopass_commission") {
            $url = $this->getCompleteAddress($renting) . '/viewinvoice.php?id=' . $invoice;
        } elseif ($renting->getService()->getName() == "hostbill_allopass_commission") {
            $url = $this->getCompleteAddress($renting) . '/index.php?/clientarea/invoice/' . $invoice;
        } else {
            echo "BAD RENTING";
            die();
        }


        $response = new Response();
        $response->setContent('<!doctype html>
        <html>
            <head>
                <link rel="stylesheet" href="templates/default/validate.css">
                <meta http-equiv="refresh" content="5; url=' . $url . '">
            </head>
	<body style="margin-top:30px;">
            <div class="image"></div>
            <p>Paiement valid&eacute;, redirection vers votre facture dans 5 secondes</p>
            <p>Payment is confirmed, redirect your invoice within 5 seconds</p>
	</body>
	</html>');
        $response->headers->set('Content-Type', 'application/html');
        return $response;
    }

    private function getCompleteAddress($renting) {
        foreach ($renting->getDetails() as $detail) {
            if ($detail->getDetailName()->getCanonicalName == "complete_address") {
                return $detail->getValue();
            }
        }
    }

    private function getCallbackAddress($renting) {
        foreach ($renting->getDetails() as $detail) {
            if (strstr($detail->getDetailName()->getCanonicalName, "callback")) {
                return $detail->getValue();
            }
        }
    }

}
