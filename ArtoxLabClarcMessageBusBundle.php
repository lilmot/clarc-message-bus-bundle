<?php

/**
 * ArtoxLab Clean Architecture message bus bundle
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\ClarcMessageBusBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ArtoxLabClarcMessageBusBundle extends Bundle
{
    public const CONFIG_BUNDLE_NAMESPACE = 'artox_lab_clarc_message_bus';
}
