<?php


namespace App\Controller;


use App\Form\CheckoutForm;
use App\Repository\ProductRepository;
use App\Service\NovaPoshtaService;
use App\Service\ProductService;
use Doctrine\Common\Collections\ArrayCollection;
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

    public function basketView(SessionInterface $session, ProductRepository $productRepository, ProductService $productService)
    {
        $minimal_order_price = $this->getParameter('minimal_order_price');
        $basket = $session->get('basket');
        $products = new ArrayCollection();
        $total = 0;
        if(empty($basket))
            return $this->render('cart.html.twig', ['total' => null, 'minOrder' => $minimal_order_price] );
        foreach ($basket as $index => $item) {
            $product = $productService->getProductPrice($productRepository->findOneBy(['id' => $index]))->setAmount($item);
            $products->add($product);
            if(!empty($product->getProductValue())){
                if(!empty($product->getMinimumWholesale() && $product->getAmount() >= $product->getMinimumWholesale()))
                    $total += $product->getProductValue()*$product->getWholesalePrice()*$product->getAmount();
                else
                    $total += $product->getProductValue()*$product->getRetailPrice()*$product->getAmount();
            }
            else{
                if(!empty($product->getMinimumWholesale() && $product->getAmount() >= $product->getMinimumWholesale()))
                    $total += $product->getWholesalePrice()*$product->getAmount();
                else
                    $total += $product->getRetailPrice()*$product->getAmount();
            }
        }
        return $this->render('cart.html.twig',['products' => $products, 'total' => $total, 'minOrder' => $minimal_order_price]);
    }

    public function checkout(SessionInterface $session, Request $request)
    {
        if(empty($session->get('basket')))
            return $this->redirectToRoute('basket_view');

        $form = $this->createForm(CheckoutForm::class);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $data = $form->getData();
            switch ($data['type']){
                case 1:
                    $data['type'] = 'Самовывоз';
                    break;
                case 2:
                    $data['type'] = 'Новая почта';
                    break;
                case 3:
                    $data['type'] = 'Другое';
                    break;
            }
        }

        return $this->render('checkout.html.twig', ['form' => $form->createView()]);
    }

    public function minusProduct($id, SessionInterface $session)
    {
        $basket = $session->get('basket');
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

    public function getPostOffices(NovaPoshtaService $novaPoshtaService)
    {
        return new Response(json_encode($novaPoshtaService->getJson()));
    }

}