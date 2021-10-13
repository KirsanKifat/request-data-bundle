<?php

namespace KirsanKifat\RequestDataBundle;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
final class Events
{
    /**
     * The FINISH event occurs when request data formation is finished.
     *
     * @Event("KirsanKifat\RequestDataBundle\Event\FinishEvent")
     */
    public const FINISH = 'request_data.finish';
}
