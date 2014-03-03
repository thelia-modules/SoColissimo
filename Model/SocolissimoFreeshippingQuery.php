<?php

namespace SoColissimo\Model;

use SoColissimo\Model\Base\SocolissimoFreeshippingQuery as BaseSocolissimoFreeshippingQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'socolissimo_freeshipping' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SocolissimoFreeshippingQuery extends BaseSocolissimoFreeshippingQuery
{
    public function getLast()
    {
        return $this->orderById('desc')->findOne()->getActive();
    }
} // SocolissimoFreeshippingQuery
