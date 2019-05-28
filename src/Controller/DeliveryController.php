<?php


namespace App\Controller;


use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DeliveryController extends AbstractController
{
    public function addToBasket(Request $request, ProductRepository $productRepository, SessionInterface $session)
    {
        $product = $productRepository->findOneBy(['slug' => $request->get('slug')]);
        if(empty($product))
            return new Response('Product not found', 404);

        $id = $product->getId();
        $basket = $session->get('basket');
        if(empty($basket))
            $basket = [];

        if(isset($basket[$id]))
            $basket[$id]++;
        else
            $basket[$id] = 1;

        $session->set('basket', $basket);
        return new Response(null);
    }

    public function basketView(SessionInterface $session)
    {
        dd($session->get('basket'));
    }

    public function minusProduct($id)
    {
        $basket = $this->session->get('basket');
        if($basket[$id] == 1)
            unset($basket[$id]);
        else
            $basket[$id] -= 1;


    }

    public function clearBasket()
    {
//        $this->session->remove('basket');
        return new Response(null);
    }

}