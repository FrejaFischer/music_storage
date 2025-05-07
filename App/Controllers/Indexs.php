<?php

namespace App\Controllers;

use App\Helpers\ResponseHelper;
use App\Helpers\LinkBuilder;

class Indexs extends \Core\Controller
{
    public function indexAction(): void
    {
        $links = LinkBuilder::allLinks(); // Get HATEOAS links

        ResponseHelper::jsonResponse('Welcome to Digital Music API', $links);
    }
}