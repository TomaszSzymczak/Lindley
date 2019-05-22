<?php
namespace Lindley\Funnels;

class FunnelOrder
{
    /** @var \Closure */
    public $orderBy;

    /** @var string ASC|DESC */
    public $order;

    /**
     * @param \Closure $orderBy func. used to determine how to get value to compare
     * @param string   $order   ASC|DESC
     * @param string   $type    eg. numeric
     */
    public function __construct( \Closure $orderBy, string $order, string $type )
    {
        $this->orderBy = $orderBy;
        $this->order = $order;
    }
}
