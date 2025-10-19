<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('EmptyState')]
class EmptyState
{
    public string $icon = 'fa-inbox';
    public string $title = 'No items found';
    public string $text = '';
    public ?string $actionUrl = null;
    public ?string $actionText = null;
}
