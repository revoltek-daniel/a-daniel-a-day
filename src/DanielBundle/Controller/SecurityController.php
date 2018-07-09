<?php

namespace DanielBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 *
 * @package AutodeskBundle\Controller
 */
class SecurityController extends Controller
{
    /**
     * @param Request             $request
     * @param AuthenticationUtils $authUtils
     *
     * @return array
     * @Template()
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
         // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return  array(
            'last_username' => $lastUsername,
            'error'         => $error,
        );
    }

    public function logoutAction()
    {
    }
}
