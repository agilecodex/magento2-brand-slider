<?php
/**
 *  Copyright © Agile Codex Ltd. All rights reserved.
 *  License: https://www.agilecodex.com/license-agreement
 */
namespace Acx\BrandSlider\Model;

/**
 * Is active or inactive
 *
 * @author Agile Codex
 */
class Status
{
    public const STATUS_ENABLED = 1;
    public const STATUS_DISABLED = 2;

    /**
     * Get available statuses.
     *
     * @return []
     */
    public function getAvailableStatuses()
    {
        return [
            self::STATUS_ENABLED => __('Enabled')
            , self::STATUS_DISABLED => __('Disabled'),
        ];
    }
}
