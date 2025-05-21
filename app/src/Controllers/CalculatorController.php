<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use App\Exceptions\HttpNoContentException;
use App\Models\CalculatorModel;
use App\Core\AppSettings;


/**
 * Controller that is for handling calculation resources
 */
class CalculatorController extends BaseController
{

    public function __construct(protected AppSettings $appSettings, private CalculatorModel $calculator_model)
    {
        //To initialize the validator
        parent::__construct();
    }


    /**
     *  Calculates total calories based on input data.
     * @param \Psr\Http\Message\ServerRequestInterface $request HTTP request containing input data.
     * @param \Psr\Http\Message\ResponseInterface $response  HTTP response.
     * @throws \Slim\Exception\HttpBadRequestException If input data is missing or empty.
     * @throws \App\Exceptions\HttpNoContentException If no result is returned.
     * @return Response JSON response with calculated calorie result.
     */
    public function handleCalculateCalories(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        //if no data
        if (empty($data[0])) {
            throw new HttpBadRequestException($request, "Data passed is empty");
        }

        // calculates using the model
        $result = $this->calculator_model->calculateCalories($data[0]);

        if (empty($result)) {
            throw new HttpNoContentException($request, "Empty result");
        }

        return $this->renderJson($response, $result);
    }


    /**
     * Calculates daily fiber intake based on user data.
     * @param \Psr\Http\Message\ServerRequestInterface $request HTTP request with input data.
     * @param \Psr\Http\Message\ResponseInterface $response HTTP response.
     * @throws \Slim\Exception\HttpBadRequestException If input is missing or invalid.
     * @throws \App\Exceptions\HttpNoContentException If no result is returned.
     * @return Response JSON response with fiber intake result.
     */
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


    /**
     * Calculates Body Mass Index (BMI) from provided weight and height.
     * @param \Psr\Http\Message\ServerRequestInterface $request  HTTP request with user data.
     * @param \Psr\Http\Message\ResponseInterface $response  HTTP response.
     * @throws \Slim\Exception\HttpBadRequestException If input is missing or invalid.
     * @throws \App\Exceptions\HttpNoContentException If result is empty.
     * @return Response  JSON response with BMI value and category.
     */
    public function handleCalculateBMI(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        if (empty($data) || !isset($data[0])) {
            throw new HttpBadRequestException($request, "Data passed is empty");
        }

        $result = $this->calculator_model->calculateBMI($data[0]);

        if (empty($result)) {
            throw new HttpNoContentException($request, "Empty result");
        }

        return $this->renderJson($response, $result);
    }
}
