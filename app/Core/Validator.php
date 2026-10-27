<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Validador declarativo y ligero.
 *
 * Reglas soportadas: required, string, email, numeric, integer,
 * min:n, max:n, in:a,b,c y phone.
 */
final class Validator
{
    /** @var array<string, string> */
    private array $errors = [];

    /** @var array<string, mixed> */
    private array $data;

    /** @var array<string, string> */
    private array $rules;

    private function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    public static function make(array $data, array $rules): self
    {
        $validator = new self($data, $rules);
        $validator->run();

        return $validator;
    }

    public function passes(): bool
    {
        return $this->errors === [];
    }

    public function fails(): bool
    {
        return !$this->passes();
    }

    /** @return array<string, string> */
    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): string
    {
        return (string) (reset($this->errors) ?: 'Revisa los datos del formulario.');
    }

    /** @return array<string, mixed> */
    public function validated(): array
    {
        $result = [];

        foreach (array_keys($this->rules) as $field) {
            $value = $this->data[$field] ?? null;
            if (is_string($value)) {
                $value = trim($value);
            }
            $result[$field] = $value;
        }

        return $result;
    }

    private function run(): void
    {
        foreach ($this->rules as $field => $ruleString) {
            $rules = array_filter(array_map('trim', explode('|', $ruleString)));
            $label = $this->label($field);
            $value = $this->data[$field] ?? null;
            $isEmpty = $value === null || $value === '' || (is_array($value) && $value === []);

            if ($isEmpty) {
                if (in_array('required', $rules, true)) {
                    $this->errors[$field] = "El campo {$label} es obligatorio.";
                }
                continue;
            }

            if (!is_scalar($value)) {
                $this->errors[$field] = "El campo {$label} no tiene un formato válido.";
                continue;
            }

            $value = trim((string) $value);
            $isNumericField = in_array('numeric', $rules, true) || in_array('integer', $rules, true);

            foreach ($rules as $rule) {
                if ($rule === 'required' || $rule === 'string') {
                    continue;
                }

                [$name, $param] = array_pad(explode(':', $rule, 2), 2, null);

                $error = match ($name) {
                    'email' => filter_var($value, FILTER_VALIDATE_EMAIL) === false
                        ? "Ingresa un correo electrónico válido en {$label}."
                        : null,
                    'numeric' => !is_numeric($value)
                        ? "El campo {$label} debe ser numérico."
                        : null,
                    'integer' => filter_var($value, FILTER_VALIDATE_INT) === false
                        ? "El campo {$label} debe ser un número entero."
                        : null,
                    'min' => $this->checkMin($value, (float) $param, $isNumericField)
                        ? null
                        : "El campo {$label} debe tener al menos {$param} caracteres o unidades.",
                    'max' => $this->checkMax($value, (float) $param, $isNumericField)
                        ? null
                        : "El campo {$label} no debe superar {$param} caracteres o unidades.",
                    'in' => !in_array($value, explode(',', (string) $param), true)
                        ? "El valor seleccionado en {$label} no es válido."
                        : null,
                    'phone' => !$this->isPhone($value)
                        ? "Ingresa un teléfono válido en {$label}."
                        : null,
                    default => null,
                };

                if ($error !== null) {
                    $this->errors[$field] = $error;
                    break;
                }
            }
        }
    }

    private function checkMin(string $value, float $min, bool $numericField): bool
    {
        return $numericField ? (float) $value >= $min : mb_strlen($value) >= $min;
    }

    private function checkMax(string $value, float $max, bool $numericField): bool
    {
        return $numericField ? (float) $value <= $max : mb_strlen($value) <= $max;
    }

    private function isPhone(string $value): bool
    {
        if (preg_match('/^[0-9+()\-\s]{6,20}$/', $value) !== 1) {
            return false;
        }

        $digits = preg_replace('/\D+/', '', $value) ?? '';

        return strlen($digits) >= 6 && strlen($digits) <= 15;
    }

    private function label(string $field): string
    {
        $labels = [
            'name' => 'nombre',
            'phone' => 'teléfono',
            'email' => 'correo electrónico',
            'address' => 'dirección',
            'dni' => 'DNI / CE',
            'age' => 'edad',
            'area' => 'área',
            'zone' => 'zona',
            'type' => 'tipo',
            'detail' => 'detalle',
            'notes' => 'nota adicional',
            'pay_method' => 'método de pago',
            'position' => 'puesto de interés',
            'coupon' => 'cupón',
            'password' => 'contraseña',
            'password_confirmation' => 'confirmación de contraseña',
            'terms' => 'aceptación de la política de privacidad',
        ];

        return $labels[$field] ?? str_replace('_', ' ', $field);
    }
}
