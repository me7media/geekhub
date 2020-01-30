<?php

namespace App\CatalogBundle\Interfaces;


use App\Entity\User;

interface AuthorInterface
{
    public function getAuthor(): ?User;

    public function setAuthor(?User $author);
}