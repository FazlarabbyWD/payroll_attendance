<?php
namespace App\Services;

use App\Services\BankServiceInterface;
use App\Repositories\BankRepositoryInterface;

class BankService implements BankServiceInterface{

     protected $bankRepository;


    public function __construct(BankRepositoryInterface $bankRepository)
    {
        $this->bankRepository = $bankRepository;

    }
    public function getBanks(){
         return $this->bankRepository->getAll();

    }
}
