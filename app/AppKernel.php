<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),

            
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            
            new JMS\SerializerBundle\JMSSerializerBundle(),
            
            new Genemu\Bundle\FormBundle\GenemuFormBundle(),
            
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            
            new Sinenco\CoreBundle\SinencoCoreBundle(),
            new Sinenco\UserBundle\SinencoUserBundle(),
            
            new Shop\UserBundle\ShopUserBundle(),
            new Shop\CartBundle\ShopCartBundle(),
            new Shop\ProductBundle\ShopProductBundle(),
            
            new FOS\UserBundle\FOSUserBundle(),
            new FOS\RestBundle\FOSRestBundle(),

            new Shop\CoreBundle\ShopCoreBundle(),
            
            new Sonata\IntlBundle\SonataIntlBundle(),
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\MediaBundle\SonataMediaBundle(),
            new Application\Sonata\MediaBundle\ApplicationSonataMediaBundle(),    
            new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),

            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            
            new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),
            

            new Payum\Bundle\PayumBundle\PayumBundle(),
            new Shop\PaymentBundle\ShopPaymentBundle(),
            
            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
            new Services\CoreBundle\ServicesCoreBundle(),
            new Sinenco\BlogBundle\SinencoBlogBundle(),
            new Services\AllopassBundle\ServicesAllopassBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();      
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
