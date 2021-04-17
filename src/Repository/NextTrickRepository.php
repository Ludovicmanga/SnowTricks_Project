<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class NextTrickRepository
{
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Trick::class);
    }

    /**
     * Returns total number of comments for a trick
     * @return void
     */
    public function findNextTricks()
    {
        return $this->repository->findById(84);
    }  
    
}