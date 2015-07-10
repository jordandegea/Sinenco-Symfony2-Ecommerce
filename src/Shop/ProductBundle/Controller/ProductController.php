<?php

namespace Shop\ProductBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Shop\ProductBundle\Form\ReviewType;
use Shop\ProductBundle\Entity\Review;

class ProductController extends BaseController {

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function addReviewAction(Request $request, $product_idOrSlug) {

        $repository = $this->getDoctrine()->getManager()->getRepository('ShopProductBundle:Product');

        if (is_numeric($product_idOrSlug)) {
            $product = $repository->find($product_idOrSlug);
        } else {
            $product = $repository->findOneByCanonicalName($product_idOrSlug);
        }


        if ($product == null) {
            throw $this->createNotFoundException($this->get('translator')->trans("product_dont_exist"));
        }

        $review = new Review();
        $form = $this->get('form.factory')->create(new ReviewType(), $review);


        if ($form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($review);

            $em->flush();

            $translator = $this->get('translator') ;
            
            $request->getSession()
                    ->getFlashBag()
                    ->add('success', $translator->trans('shop.product.reviews.added'));


            return $this->redirect(
                            $this->generateUrl(
                                    'shop_product', array(
                                'product_idOrSlug' => $product_idOrSlug
                                    )
                            )
            );
        }

        return $this->render('ShopProductBundle:Reviews:add.html.twig', array(
                    'product' => $product,
                    'form' => $form->createView(),
        ));
    }

    public function productAction($product_idOrSlug) {

        $repository = $this->getDoctrine()->getManager()->getRepository('ShopProductBundle:Product');

        if (is_numeric($product_idOrSlug)) {
            $product = $repository->find($product_idOrSlug);
        } else {
            $product = $repository->findOneByCanonicalName($product_idOrSlug);
        }


        if ($product == null) {
            throw $this->createNotFoundException($this->get('translator')->trans("product_dont_exist"));
        }
        $category = $product->getCategory();
        //$image = $product->getImage() ;
        //$imageProvider = $this->get('sonata.media.provider.image');

        return $this->render('ShopProductBundle:Products:product.html.twig', array(
                    'product' => $product,
                    'category' => $category,
        ));
    }

    public function listAction($category) {
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('ShopProductBundle:Product');

        // If the category param is null, so we display nothing
        // Else we display all products associated with the category
        if ($category == null) {
            $products = null;
        } else {
            $categoryRepository = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('ShopProductBundle:Category');

            if (is_numeric($category)) {
                $category = $categoryRepository->find($category);
                var_dump($category);
            } else {
                $category = $categoryRepository->findOneByCanonicalName($category);
            }

            if ($category == null) {
                throw $this->createNotFoundException($this->get('translator')->trans("category_dont_exist"));
            }

            $products = $repository->findBy(array('category' => $category)); // Find products matching some custom criteria.
        }

        return $this->render('ShopProductBundle:Products:list.html.twig', array(
                    'products' => $products,
                    'category' => $category
        ));
    }

    public function categoriesAction(Request $request, $category = null, $followingCategories = true) {


        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository('ShopProductBundle:Category');

        if ($category == null) {
            $categories = $repo->findBy(array('parentCategory' => null));
            $parents = null;
        } else {
            $categories = $repo->findBy(array('parentCategory' => $repo->find($category)));
            $parents = array();
            $currentParent = $repo->find($category);
            while ($currentParent != null) {
                $parents[] = $currentParent;
                $currentParent = $currentParent->getParentCategory();
            }
        }

        return $this->render('ShopProductBundle:Products:categories.html.twig', array(
                    'categories' => $categories,
                    'parents' => $parents,
                    'followingCategories' => $followingCategories
        ));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function purchasesAction(Request $request) {

        $repository = $this->getDoctrine()->getManager()->getRepository('ShopProductBundle:Purchases');
        $purchases = $repository->findByUser($user = $this->getUser());


        return $this->render('ShopProductBundle:Products:purchases.html.twig', array(
                    'purchases' => $purchases
        ));
    }

}
