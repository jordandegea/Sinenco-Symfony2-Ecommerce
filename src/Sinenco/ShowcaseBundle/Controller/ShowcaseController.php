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

        $pages = $repository->findBy(['active'=> true]);

        $showcase = new \stdClass();

        $showcase->pages = array();


        foreach ($pages as $page) {
            $defaultLanguagePage = $firstLanguagePage = $selectedLanguagePage = null;

            foreach ($page->getLanguagePages() as $languagePage) {
                if ($languagePage->getLanguage() == $_locale) {
                    $selectedLanguagePage = $languagePage;
                } elseif ($languagePage->getLanguage() == "en") {
                    $defaultLanguagePage = $languagePage;
                } elseif ($firstLanguagePage == null) {
                    $firstLanguagePage = $languagePage;
                }
            }
            if ($selectedLanguagePage != null) {
                $showcase->pages[] = $selectedLanguagePage;
            } elseif ($defaultLanguagePage != null) {
                $showcase->pages[] = $defaultLanguagePage;
            } elseif ($firstLanguagePage != null) {
                $showcase->pages[] = $firstLanguagePage;
            }
        }

        return $this->render('SinencoShowcaseBundle:Showcase:index.html.twig', array('showcase' => $showcase));
    }

    public function pageAction($slug, $_locale) {
        $repository = $this->getDoctrine()->getManager()->getRepository('SinencoShowcaseBundle:Page');

        $page = $repository->findOneByCanonicalName($slug);

        $defaultLanguagePage = $firstLanguagePage = $selectedLanguagePage = null;

        foreach ($page->getLanguagePages() as $languagePage) {
            if ($languagePage->getLanguage() == $_locale) {
                $selectedLanguagePage = $languagePage;
            } elseif ($languagePage->getLanguage() == "en") {
                $defaultLanguagePage = $languagePage;
            } elseif ($firstLanguagePage == null) {
                $firstLanguagePage = $languagePage;
            }
        }
        if ($selectedLanguagePage != null) {
            $page = $selectedLanguagePage;
        } elseif ($defaultLanguagePage != null) {
            $page = $defaultLanguagePage;
        } elseif ($firstLanguagePage != null) {
            $page = $firstLanguagePage;
        }


        return $this->render('SinencoShowcaseBundle:Showcase:page.html.twig', array('page' => $page));
    }

}
