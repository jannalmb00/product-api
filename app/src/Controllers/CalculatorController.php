<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use App\Exceptions\HttpNoContentException;
use App\Exceptions\HttpInvalidInputException;
use App\Validation\ValidationHelper;
use App\Models\CalculatorModel;
use App\Models\BaseModel;
use App\Services\UserService;
use App\Core\AppSettings;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;



class CalculatorController extends BaseController
{

    public function __construct(protected AppSettings $appSettings, private CalculatorModel $calculator_model)
    {
        //To initialize the validator
        parent::__construct();
    }

    public function handleCalculateCalories(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        if (empty($data[0])) {
            throw new HttpBadRequestException($request, "Data passed is empty");
        }

        $result = $this->calculator_model->calculateCalories($data[0]);

        if (empty($result)) {
            throw new HttpNoContentException($request, "Empty result");
        }

        return $this->renderJson($response, $result);
    }

    public function handleCalculateFiber(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        if (empty($data) || !isset($data[0])) {
            throw new HttpBadRequestException($request, "Data passed is empty or invalid format");
        }

        $result = $this->calculator_model->calculateFiberIntake($data[0]);

        if (empty($result)) {
            throw new HttpNoContentException($request, "Empty result");
        }

        return $this->renderJson($response, $result);
    }
}
