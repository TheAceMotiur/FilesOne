<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginationHelper
{
    /**
     * Generate pagination for an array
     * @param array $items
     * @param int $perPage
     * @return mixed
     */
    public function paginate(
        array $items,
        int $perPage = 3
    ) {
        if (!$items) {
            return false;
        }
        // Get current page form url e.x. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        // Create a new Laravel collection from the array data
        $itemCollection = collect($items);
        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection
            ->slice(
                ($currentPage * $perPage) - $perPage,
                $perPage
            )->all();
        // Create our paginator and pass it to the view
        $paginatedItems = new LengthAwarePaginator(
            $currentPageItems,
            count($itemCollection),
            $perPage,
        );
        // set url path for generated links
        $paginatedItems->setPath(request()->url())->onEachSide(1);
        return $paginatedItems;
    }

}
