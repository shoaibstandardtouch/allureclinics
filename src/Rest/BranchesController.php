<?php

namespace AllureClinics\Rest;

use WP_REST_Request;
use WP_REST_Response;
use AllureClinics\Repositories\BranchRepository;

class BranchesController {

    private BranchRepository $repository;

    public function __construct(BranchRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * Get branches list
     * GET /allure/v1/branches
     */
    public function get_branches(WP_REST_Request $request): WP_REST_Response {
        $branches = $this->repository->getAll();
        return new WP_REST_Response($branches, 200);
    }
}
