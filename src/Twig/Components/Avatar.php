<?php

namespace App\Twig\Components;

use App\Entity\User;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Avatar')]
class Avatar
{
    public User $user;
    public string $size = 'md'; // xs, sm, md, lg, xl
    public bool $showName = false;
    public bool $clickable = false;

    public function getSizeClass(): string
    {
        return match ($this->size) {
            'xs' => 'avatar-xs',
            'sm' => 'avatar-sm',
            'md' => 'avatar-md',
            'lg' => 'avatar-lg',
            'xl' => 'avatar-xl',
            default => 'avatar-md',
        };
    }

    public function getInitials(): string
    {
        $first = strtoupper(substr($this->user->getFirstName() ?? '', 0, 1));
        $last = strtoupper(substr($this->user->getLastName() ?? '', 0, 1));
        return $first . $last;
    }
}
