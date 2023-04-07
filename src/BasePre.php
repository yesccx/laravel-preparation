<?php

declare(strict_types = 1);

namespace Yesccx\Preparation;

use Illuminate\Http\Request;
use Yesccx\Preparation\Exceptions\PreparationException;

/**
 * 前置验证 基类
 */
abstract class BasePre
{
    /**
     * @param Request $request
     */
    public function __construct(
        public Request $request
    ) { }

    /**
     * Fail
     *
     * @param string $message The Exception message to throw.
     * @param int $code The Exception code.
     *
     * @throws PreparationException
     */
    protected function fail(string $message, int $code = 0)
    {
        throw new PreparationException($message, $code);
    }

    /**
     * Get current action name
     *
     * @return null|string
     */
    protected function getActionName(): ?string
    {
        return $this->request?->route()?->getActionMethod();
    }
}
