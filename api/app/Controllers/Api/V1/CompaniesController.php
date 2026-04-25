<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\CompanyModel as CompaniesModel;
use App\Validation\CompanyValidation;

class CompaniesController extends ResourceController
{
    protected $modelName = CompaniesModel::class;
    protected $format    = 'json';

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        return $this->respond($this->model->orderBy('name', 'ASC')->findAll());
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        $company = $this->model->asObject()->find($id);

        if ($company === null) {
            return $this->failNotFound(
                description: 'Empresa não encontrada.',
                code: ResponseInterface::HTTP_NOT_FOUND
            );
        }

        return $this->respond(data: $company, status: ResponseInterface::HTTP_OK);
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $rules = (new CompanyValidation())->getRules();

        if (!$this->validate($rules)) {
            return $this->failValidationErrors(
                $this->validator->getErrors(),
                'Erro de validação.',
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        $inputRequest = esc($this->request->getJSON(assoc: true));

        $id =$this->model->insert($inputRequest);

        $companyCreated = $this->model->asObject()->find($id);

        return $this->respondCreated(data: $companyCreated, message: 'Empresa criada com sucesso.');
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        //
    }
}
