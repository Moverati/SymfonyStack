<?php

namespace FOS\UserBundle\Twig;

use Symfony\Component\Security\Core\SecurityContext;
use FOS\UserBundle\Model\UserInterface;
use Twig_Function_Method;
use Twig_Extension;

class UserExtension extends Twig_Extension
{
    protected $securityContext;

    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * Returns a list of global functions to add to the existing list.
     *
     * @return array An array of global functions
     */
    public function getFunctions()
    {
        return array(
            'fos_user_getUser'         => new Twig_Function_Method($this, 'getUser'),
            'fos_user_isUser'          => new Twig_Function_Method($this, 'isUser'),
            'fos_user_isAuthenticated' => new Twig_Function_Method($this, 'isAuthenticated'),
            'fos_user_isAnonymous'     => new Twig_Function_Method($this, 'isAnonymous'),
        );
    }

    /**
     * Returns the authenticated user, if any
     *
     * @return FOS\UserBundle\Model\User
     */
    public function getUser()
    {
        return $this->securityContext->getToken()->getUser();
    }

    /**
     * Tells whether the authenticated user is this user
     *
     * @param UserInterface $user
     * @return bool
     */
    public function isUser(UserInterface $user)
    {
        $authenticatedUser = $this->getUser();

        return $authenticatedUser instanceof UserInterface && $authenticatedUser->isUser($user);
    }

    /**
     * Tell whether or not a user is logged in
     *
     * @return boolean
     */
    public function isAuthenticated()
    {
        return null !== $this->securityContext->getToken();
    }

    /**
     * Tell whether or not the user is anonymous
     *
     * @return boolean
     */
    public function isAnonymous()
    {
        return false === $this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED');
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'fos_user';
    }
}
