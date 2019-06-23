<?php


namespace App\Controller\Admin;


use App\Form\CommonInfoForm;
use App\Mappers\CommonInfo;
use App\Repository\ProductRepository;
use App\Service\CommonInfoService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

class MainController extends AbstractController
{

    public function index()
    {
        return $this->render('admin/admin_index.html.twig');
    }

    public function productsView(ProductRepository $productRepository, PaginatorInterface $paginator, Request $request)
    {
        $products  = $paginator->paginate(
            $productRepository->getProductsQuery(),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 30)
        );

        return $this->render('admin/admin_product.html.twig', ['products' => $products]);
    }

    public function getCommonSettings($type, Request $request, CommonInfoService $commonInfoService)
    {
        $settings = [];
        $settings[] = ['Минимальная сумма заказа', $commonInfoService->getParameter('minimal_order_price')];
        $settings[] = ['Общая стоимость доллара', $commonInfoService->getParameter('usd_value')];
        $settings[] = ['Общая стоимость евро', $commonInfoService->getParameter('eur_value')];
        $settings[] = ['Номер телефона', $commonInfoService->getParameter('phone_number')];
        $settings[] = ['Адрес магазина', $commonInfoService->getParameter('address')];
        $settings[] = ['Контактное лицо', $commonInfoService->getParameter('name_surname')];
        if($type == 'view')
            return $this->render('admin/common_setting.html.twig', ['settings' => $settings]);
        elseif ($type == 'edit'){
            $dto = CommonInfo::arrayToDto($commonInfoService->getData()->toArray());
            $form = $this->createForm(CommonInfoForm::class, $dto);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $commonInfoService
                    ->setParameter('minimal_order_price', $dto->getMinimum())
                    ->setParameter('usd_value', $dto->getUsd())
                    ->setParameter('eur_value', $dto->getEur())
                    ->setParameter('phone_number', $dto->getNumber())
                    ->setParameter('address', $dto->getAddress())
                    ->setParameter('name_surname', $dto->getName())
                    ->flush();
                return $this->redirectToRoute('common_settings', ['type' => 'view']);
            }
            return $this->render('admin/common_setting.html.twig', ['form' => $form->createView()]);
        }
        else
            return new Response('', 404);
    }
}