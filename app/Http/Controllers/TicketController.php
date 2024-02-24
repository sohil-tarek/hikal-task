<?php

namespace App\Http\Controllers;

use App\repositories\interface\BasicRepositoryInterface;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    protected $repository;

    public function __construct(BasicRepositoryInterface $repository)
    {
            $this->repository = $repository;
    }

    public function index()
    {
        return $this->repository->getTickets();

    }
}
