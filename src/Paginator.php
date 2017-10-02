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
            $this->appends('end', session()->get('accountant.api.end'));
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
            $this->appends('start', session()->get('accountant.api.start'));

            return $this->url($this->currentPage() + 1);
        }
    }

    /**
     * Remove start and end points from the query.
     */
    protected function resetQuery()
    {
        array_forget($this->query, ['start', 'end']);
    }

    /**
     * Determine if there are enough items to split into multiple pages.
     *
     * @return null|string
     */
    public function hasPages()
    {
        return $this->nextPageUrl() || $this->previousPageUrl();
    }

    /**
     * Determine if there are more items in the data source.
     *
     * @return null|string
     */
    public function hasMorePages()
    {
        return $this->morePages;
    }
}
