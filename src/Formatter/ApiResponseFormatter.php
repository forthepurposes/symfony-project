<?php

declare(strict_types=1);

namespace App\Formatter;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseFormatter
{
    private array $data = [];
    private string $message = 'success';
    private array $errors = [];
    private int $status = Response::HTTP_OK;
    private array  $additionalData = [];

    public function withData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function withMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function withErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    public function withStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function withAdditionalData($additionalData): self
    {
        $this->additionalData = $additionalData;

        return $this;
    }

    public function response(): JsonResponse
    {
        return new JsonResponse(
            [
                'data' => $this->data,
                'message' => $this->message,
                'errors' => $this->errors,
                'status' => $this->status,
                'additionalData' => $this->additionalData
            ], $this->status
        );
    }

}
