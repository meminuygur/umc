<?php

/**
 *
 * UMC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @copyright Marius Strajeru
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Marius Strajeru <ultimate.module.creator@gmail.com>
 *
 */

declare(strict_types=1);

namespace App\Model\Attribute\Serialized\Type;

use App\Model\Attribute\Serialized;

class Factory
{
    /**
     * @var string
     */
    private const DEFAULT_TYPE_CLASS = BaseType::class;
    /**
     * @var array
     */
    private $typeMap;
    /**
     * @var \Twig\Environment;
     */
    private $twig;

    /**
     * Factory constructor.
     * @param \Twig\Environment $twig
     * @param array $typeMap
     */
    public function __construct(\Twig\Environment $twig, array $typeMap)
    {
        $this->twig = $twig;
        $this->typeMap = array_filter(
            $typeMap,
            function ($item) {
                return isset($item['can_be_serialized']) && $item['can_be_serialized'];
            }
        );
    }

    /**
     * @param Serialized $serialized
     * @return BaseType
     */
    public function create(Serialized $serialized): BaseType
    {
        $type = $serialized->getType();
        if (!isset($this->typeMap[$type])) {
            throw new \InvalidArgumentException("There is no config for serialized type {$type}");
        }
        $config = $this->typeMap[$type];
        $class = $config['serialized_class'] ?? self::DEFAULT_TYPE_CLASS;
        return new $class($this->twig, $serialized, $config);
    }
}
