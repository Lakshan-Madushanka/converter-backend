<?php

namespace App\Enums\Job;

enum Status: int
{
    case PENDING = 1;
    case QUEUED = 2;
    case ERROR = 3;
    case SUCCESS = 4;
}