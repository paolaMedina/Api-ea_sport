<?php

namespace App\Responder;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;


trait ApiResponse
{

    protected function paginate($collection)
    {

        $page    = LengthAwarePaginator::resolveCurrentPage();
        $perPage = request()->has('per_page') ? request()->per_page : 15;
        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page);
        $paginated->appends(request()->all());

        return $paginated;
    }

    protected function showAll($collection, $code = 200)
    {
        if (!request()->has('page') || 'false' == request()->pagination) {
            return $this->successResponse(['data' => $collection], $code);
        }

        $collection = collect($this->paginate($collection));
        return $this->successResponse(
            [
                'Page'  => $collection['current_page'],
                'totalPages' => ceil($collection['total'] / $collection['per_page']),
                'Items'      => count($collection['data']),
                'totalItems' => $collection['total'],
                'players' => $collection['data']

            ],
            $code
        );
    }

    protected function successResponse($data, $code = 200)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($mesaage, $code)
    {
        return response()->json(['message' => $mesaage, 'code' => $code], $code);
    }
}
