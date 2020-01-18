<?php

namespace Wappr\Cloudflare\DataSets\FirewallActivityLog;

use DateTime;
use GraphQL\Query;
use GraphQL\RawObject;
use GraphQL\Exception\ArgumentException;
use GraphQL\Exception\InvalidSelectionException;
use Wappr\Cloudflare\Contracts\DataSetInterface;
use Wappr\Cloudflare\Contracts\SelectionSetInterface;

/**
 * Firewall Activity Log.
 *
 * Node: firewallEventsAdaptive
 *
 * @see https://developers.cloudflare.com/analytics/graphql-api/features/data-sets/
 */
class FirewallEventsAdaptive implements DataSetInterface
{
    /**
     * An array of SelectionSetInterface.
     *
     * @var array<int, SelectionSetInterface>
     */
    protected $selectionSet = [];

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @var int
     */
    protected $limit;

    public function __construct(SelectionSetInterface $selectionSet, DateTime $date, $limit)
    {
        $this->addSelectionSet($selectionSet);

        $this->date  = $date;
        $this->limit = $limit;
    }

    /**
     * @return GraphQL\Query
     *
     * @throws ArgumentException
     * @throws InvalidSelectionException
     */
    public function getDataSet()
    {
        $query = new Query('firewallEventsAdaptive');
        $query->setArguments([
            'limit'  => $this->limit,
            // @TODO - need a way to create these raw filters with all the possible variations.
            'filter' => new RawObject('{datetime_gt:"2020-01-15T13:00:00Z", datetime_lt:"2020-01-16T13:00:00Z"}'),
        ]);

        $query->setSelectionSet($this->selectionSet);

        return $query;
    }

    /**
     * @return $this
     */
    public function addSelectionSet(SelectionSetInterface $selectionSet)
    {
        if (is_array($selectionSet->getSelection())) {
            $this->selectionSet = $selectionSet->getSelection();

            return $this;
        }

        $this->selectionSet[] = $selectionSet->getSelection();

        return $this;
    }
}
