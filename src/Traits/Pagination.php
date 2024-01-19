<?php

namespace App\Traits;

use App\Service\AbstractService;
use Symfony\Component\HttpFoundation\Request;

trait Pagination
{
    private $limit = 10;

    public function getItens(Request $request, AbstractService $service, array $criteria = []): ?array
    {
        $page = $request->query->has('page') ? (int) $request->query->get('page') : 1;
        $showTable = true;

        $items = $service->findItens($criteria, $this->limit, $page);
        $pages = ceil($items->count() / $this->limit);
        
        if($page > 1 && $page > $pages) {
            $showTable = false;
            $this->addFlash('danger', 'Pagina nao existe');
        }

        return compact('items', 'showTable', 'pages', 'page');
    }
}