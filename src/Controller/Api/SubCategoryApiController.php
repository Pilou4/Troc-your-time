<?php

namespace App\Controller\Api;

use App\Repository\SubCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/sub/category', name: 'api_sub_category_')]
class SubCategoryApiController extends AbstractController
{
    
#[Route('/search', name: 'search')]
public function search(Request $request, SubCategoryRepository $subCategoryRepository)
    {
        $subCategory = $subCategoryRepository->search($request->query->get('q'));
        // j'utilise le serializer de symfony pour transformer mes objet en JSON
        return $this->json(
            $subCategory, 
            200,
            [],
            ["groups" => ["sub-category:search"]]
        );
    }
}