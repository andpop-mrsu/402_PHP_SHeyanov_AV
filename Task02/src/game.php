<?php

declare(strict_types=1);

namespace SHeyanov_AV\Task02\App;

function generateExpression(): array
{
    $operands = [];
    for ($index = 0; $index < 4; $index++) {
        $operands[] = random_int(1, 50);
    }

    $availableOperators = ['+', '-', '*'];
    $operators = [];
    for ($index = 0; $index < 3; $index++) {
        $operators[] = $availableOperators[random_int(0, 2)];
    }

    $expressionParts = [(string) $operands[0]];
    for ($index = 0; $index < 3; $index++) {
        $expressionParts[] = $operators[$index];
        $expressionParts[] = (string) $operands[$index + 1];
    }

    $expression = implode('', $expressionParts);
    $correctAnswer = evaluateExpression($operands, $operators);

    return [$expression, $correctAnswer];
}

function evaluateExpression(array $operands, array $operators): int
{
    $reducedOperands = [$operands[0]];
    $reducedOperators = [];

    for ($index = 0; $index < 3; $index++) {
        $operator = $operators[$index];
        $nextOperand = $operands[$index + 1];

        if ($operator === '*') {
            $lastIndex = count($reducedOperands) - 1;
            $reducedOperands[$lastIndex] *= $nextOperand;
            continue;
        }

        $reducedOperators[] = $operator;
        $reducedOperands[] = $nextOperand;
    }

    $result = $reducedOperands[0];
    $operatorCount = count($reducedOperators);

    for ($index = 0; $index < $operatorCount; $index++) {
        $operator = $reducedOperators[$index];
        $nextOperand = $reducedOperands[$index + 1];

        if ($operator === '+') {
            $result += $nextOperand;
            continue;
        }

        $result -= $nextOperand;
    }

    return $result;
}

