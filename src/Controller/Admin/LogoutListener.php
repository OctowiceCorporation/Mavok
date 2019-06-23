<?php


namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LogoutListener extends AbstractController implements  LogoutSuccessHandlerInterface
{
    public function onLogoutSuccess(Request $request)
    {
        return new Response($this->renderView('/admin/castyl.html.twig'), 401);
    }
}