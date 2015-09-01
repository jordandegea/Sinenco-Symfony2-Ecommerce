<?php

namespace Services\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;

class ServicesRestController extends Controller {

    public function getRentingListAction($serviceName) {
        $service = $this->getDoctrine()
                ->getRepository('ServicesCoreBundle:Service')
                ->findOneByName($serviceName);
        if (!is_object($service)) {
            throw $this->createNotFoundException();
        }
        $rentingsObject = $this->getDoctrine()
                ->getRepository('ServicesCoreBundle:Renting')
                ->findByService($service);

        $rentings = array();
        foreach ($rentingsObject as $key => $value) {
            $rentings[] = $value->getJson();
        }
        $response = new Response();
        $response->setContent(json_encode($rentings));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function getLicenseFieldValueAction($serviceName, $field, $value) {

        $service = $this->getDoctrine()
                ->getRepository('ServicesCoreBundle:Service')
                ->findOneByName($serviceName);
        if (!is_object($service)) {
            throw $this->createNotFoundException();
        }
        $rentingsObject = $this->getDoctrine()
                ->getRepository('ServicesCoreBundle:Renting')
                ->findByService($service);

        $lastExpiration = new \DateTime();
        $return = ["state" => "failed"];

        foreach ($rentingsObject as $renting) {
            foreach ($renting->getDetails() as $detail) {
                if ($detail->getDetailName()->getCanonicalName() == $field) {
                    if ($field == "domain-name-required") {
                        if (strstr($detail->getValue(), $value) !== FALSE) {
                            if ($renting->getExpiration() > $lastExpiration) {
                                $lastExpiration = $renting->getExpiration();
                                $return["license"] = $renting->getLicense();
                                $return["state"] = "complete";
                            }
                        }
                    } else {
                        if ($detail->getValue() == $value) {
                            if ($renting->getExpiration() > $lastExpiration) {
                                $lastExpiration = $renting->getExpiration();
                                $return["license"] = $renting->getLicense();
                                $return["state"] = "complete";
                            }
                        }
                    }
                }
            }
        }

        $response = new Response();
        $response->setContent(json_encode($return));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}
