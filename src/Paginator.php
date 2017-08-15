<?php

namespace TomIrons\Accountant;

use Illuminate\Pagination\Paginator as BasePaginator;

class Paginator extends BasePaginator
{
    /**
     * Get the URL for the previous page.
     *
     * @return string|null
     */
    public function previousPageUrl()
    {
        $this->resetQuery();

        if ($this->currentPage() > 2) {
            $this->addQuery('ending_before', session()->get('accountant.api.ending_before'));
        }

        return parent::previousPageUrl();
    }

    /**
     * Get the URL for the next page.
     *
     * @return string|null
     */
    public function nextPageUrl()
    {
        $this->resetQuery();

        if ($this->items->count() == $this->perPage()) {
            $this->addQuery('starting_after', session()->get('accountant.api.starting_after'));

            return $this->url($this->currentPage() + 1);
        }
    }

    /**
     * Remove start and end points from the query.
     */
    private function resetQuery()
    {
        array_forget($this->query, ['ending_before', 'starting_after']);
    }
}