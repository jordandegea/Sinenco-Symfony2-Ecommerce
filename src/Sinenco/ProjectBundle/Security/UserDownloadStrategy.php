<?php

namespace Sinenco\ProjectBundle\Security;

use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Security\DownloadStrategyInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserDownloadStrategy implements DownloadStrategyInterface {

    protected $container;
    protected $translator;
    protected $times;
    protected $sessionKey = 'sonata/media/session/times';

    /**
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param int $times
     */
    public function __construct(TranslatorInterface $translator, ContainerInterface $container, $times) {
        $this->times = $times;
        $this->container = $container;
        $this->translator = $translator;
    }

    /**
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    public function isGranted(MediaInterface $media, Request $request) {
        if (!$this->container->has('session')) {
            return false;
        }

        $em = $this->container->get('doctrine')->getEntityManager();

        $projectFileRepository = $em->getRepository('SinencoProjectBundle:ProjectFile');

        $projectFile = $projectFileRepository->findOneByFile($media->getId());

        if ($projectFile != null) {

            $projectRepository = $em->getRepository('SinencoProjectBundle:Project');

            $project = $projectRepository->findOneBySpecifications($projectFile->getId());

            if ($project == null) {
                $project = $projectRepository->findOneByPropositions($projectFile->getId());
            }

            if ($project == null) {
                return false;
            }

            $token = $this->container->get('security.context')->getToken();

            if ($token == null) {
                return false;
            }

            $user = $token->getUser();

            if ($user == null || is_string($user)) {
                return false;
            }

            if ($project->getUser()->getId() == $user->getId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->translator->trans('description.session_download_strategy', array('%times%' => $this->times), 'SonataMediaBundle');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session
     */
    private function getSession() {
        return $this->container->get('session');
    }

}
