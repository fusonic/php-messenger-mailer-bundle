<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\MessengerMailerBundle\Helper;

final class RandomHelper
{
    public static function randomHex(): string
    {
        return bin2hex(random_bytes(16));
    }
}
