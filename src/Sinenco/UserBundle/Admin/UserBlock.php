<?php

namespace Sinenco\UserBundle\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BaseBlockService,
    Sonata\BlockBundle\Block\BlockServiceInterface;

class UserBlock extends BaseBlockService implements BlockServiceInterface {

    
    protected $em;

    public function __construct($type, $templating, $em)
    {
        parent::__construct($type, $templating) ;
        $this->em = $em;
    }
    
    public function buildCreateForm(FormMapper $form, BlockInterface $block) {
        
    }

    public function buildEditForm(FormMapper $form, BlockInterface $block) {
        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('url', 'url', array('required' => false)),
                array('title', 'text', array('required' => false)),
            )
        ));
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null) {
        
        $repository = $this->em->getRepository("SinencoUserBundle:User");
        
        
        
        $settings = $blockContext->getSettings();
        
        $settings["color"] = "bg-aqua" ;
        $settings["number"] = $repository->count();
        $settings["path"] = "" ;

        return $this->renderResponse($blockContext->getTemplate(), array(
                    'block' => $blockContext->getBlock(),
                    'settings' => $settings
                        ), $response);
    }

    public function getCacheKeys(BlockInterface $block) {
        
    }

    public function getJavascripts($media) {
        return [] ;
    }

    public function getName() {
        
    }

    public function getStylesheets($media) {
        return [] ;
        
    }

    public function load(BlockInterface $block) {
        
    }

    public function setDefaultSettings(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'url' => false,
            'title' => 'Users',
            'template' => ':AdminBlock:small_box.html.twig',
            'color' => "bg-aqua",
            'icon' => ""
        ));
    }

    public function validateBlock(\Sonata\AdminBundle\Validator\ErrorElement $errorElement, BlockInterface $block) {
        $errorElement
                ->with('settings.url')
                ->assertNotNull(array())
                ->assertNotBlank()
                ->end()
                ->with('settings.title')
                ->assertNotNull(array())
                ->assertNotBlank()
                ->assertMaxLength(array('limit' => 50))
                ->end();
    }

}
