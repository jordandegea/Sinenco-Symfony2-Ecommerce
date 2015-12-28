<?php

namespace Sinenco\ShowcaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sinenco\ShowcaseBundle\Entity\LanguagePage;
use Sinenco\ShowcaseBundle\Entity\Page;

class ShowcaseController extends Controller {
    /* @var $page Page */
    /* @var $languagePage LanguagePage */

    /**
     * 
     * @param type $_locale
     * @return type
     */
    public function indexAction($_locale) {
        $repository = $this->getDoctrine()->getManager()->getRepository('SinencoShowcaseBundle:Page');

        $pages = $repository->findAll();

        $showcase = new \stdClass();

        $showcase->pages = array();


        foreach ($pages as $page) {
            $defaultLanguagePage = $firstLanguagePage = $selectedLanguagePage = null;

            foreach ($page->getLanguagePages() as $languagePage) {
                echo "1;";
                if ($languagePage->getLanguage() == $_locale) {
                    $selectedLanguagePage = $languagePage;
                } elseif ($languagePage->getLanguage() == "en") {
                    $defaultLanguagePage = $languagePage;
                } elseif ($firstLanguagePage == null) {
                    $firstLanguagePage = $languagePage;
                }
            }
            if ($selectedLanguagePage != null) {
                echo "1;";
                $showcase->pages[] = $selectedLanguagePage;
            } elseif ($defaultLanguagePage != null) {
                $showcase->pages[] = $defaultLanguagePage;
                echo "3;";
            } elseif ($firstLanguagePage != null) {
                echo "2;";
                $showcase->pages[] = $firstLanguagePage;
            }
                echo "4;";
        }

        return $this->render('SinencoShowcaseBundle:Showcase:index.html.twig', array('showcase' => $showcase));
    }
    
    
    public function pageAction($slug){
        
    }

}
