<?php


namespace App\Controller;


use App\Form\CheckoutForm;
use App\Repository\ProductRepository;
use App\Service\CommonInfoService;
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
            throw $this->createNotFoundException();

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

    public function basketView(SessionInterface $session, ProductRepository $productRepository, ProductService $productService, CommonInfoService $commonInfoService)
    {
        $minimal_order_price = $commonInfoService->getParameter('minimal_order_price');
        $basket = $session->get('basket');
        $products = new ArrayCollection();
        $total = 0;
        if(empty($basket))
            return $this->render('cart.html.twig', ['total' => null, 'minOrder' => $minimal_order_price] );
        foreach ($basket as $index => $item) {
            $product = $productRepository->findOneBy(['id' => $index]);
            if(!empty($product)){
                $product = $productService->getProductPrice($productRepository->findOneBy(['id' => $index]))->setAmount($item);
                $products->add($product);
                if(!empty($product->getProductValue())){
                    if(!empty($product->getMinimumWholesale() && $product->getAmount() >= $product->getMinimumWholesale())){
                        if(!empty($product->getSale()))
                            $total += $product->getProductValue()*$product->getWholesalePrice()*$product->getAmount() - ($product->getProductValue()*$product->getWholesalePrice()*$product->getAmount()*$product->getSale()/100);
                        else
                            $total += $product->getProductValue()*$product->getWholesalePrice()*$product->getAmount();

                    }
                    else{
                        if(!empty($product->getSale()))
                            $total += $product->getProductValue()*$product->getRetailPrice()*$product->getAmount() - ($product->getProductValue()*$product->getRetailPrice()*$product->getAmount()*$product->getSale()/100);
                        else
                            $total += $product->getProductValue()*$product->getRetailPrice()*$product->getAmount();
                    }
                }
                else{
                    if(!empty($product->getMinimumWholesale() && $product->getAmount() >= $product->getMinimumWholesale())){
                        if(!empty($product->getSale()))
                            $total += $product->getWholesalePrice()*$product->getAmount() - ($product->getWholesalePrice()*$product->getAmount()*$product->getSale()/100);
                        else
                            $total += $product->getWholesalePrice()*$product->getAmount();

                    }
                    else{
                        if(!empty($product->getSale()))
                            $total += $product->getRetailPrice()*$product->getAmount() - ($product->getRetailPrice()*$product->getAmount()*$product->getSale()/100);
                        else
                            $total += $product->getRetailPrice()*$product->getAmount();
                    }
                }
                $total = intval($total * 10)/ 10;
            }
            else{
                unset($basket[$index]);
                $session->set('basket', $basket);
            }
        }
        return $this->render('cart.html.twig',['products' => $products, 'total' => $total, 'minOrder' => $minimal_order_price]);
    }

    public function checkout(SessionInterface $session, Request $request, CommonInfoService $commonInfoService)
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

        return $this->render('checkout.html.twig', ['form' => $form->createView(), 'pickup' => $commonInfoService->getParameter('address')]);
    }

    public function minusBasket($slug, SessionInterface $session, ProductRepository $productRepository)
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);
        if(empty($product))
            throw $this->createNotFoundException();

        $id = $product->getId();

        $basket = $session->get('basket');
        if(isset($basket[$id])){
            if($basket[$id] == 1)
                unset($basket[$id]);
            else
                $basket[$id] -= 1;

            $session->set('basket', $basket);
        }

        return new Response(null);
    }

    public function plusBasket($slug, ProductRepository $productRepository, SessionInterface $session)
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);
        if(empty($product))
            throw $this->createNotFoundException();

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

    public function clearBasket()
    {
//        $this->session->remove('basket');
        return new Response(null);
    }

    public function deleteFromBasket($slug, ProductRepository $productRepository, SessionInterface $session)
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);
        if(empty($product))
            throw $this->createNotFoundException();

        $id = $product->getId();
        $basket = $session->get('basket');
        if(empty($basket))
            $basket = [];

        if(isset($basket[$id]))
            unset($basket[$id]);

        $session->set('basket', $basket);

        return new Response(null);
    }

    public function getPostOffices(NovaPoshtaService $novaPoshtaService)
    {
        return new Response(json_encode($novaPoshtaService->getJson()));
    }

    public function getProductAmount(SessionInterface $session)
    {
        $basket = $session->get('basket');
        if(empty($basket))
            return new Response(0);

        $amount = 0;
        foreach ($basket as $item) {
            $amount += $item;
        }

        return new Response($amount);
    }

}