<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;

class Day21 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '5' => <<<'INPUT'
            mxmxvkd kfcds sqjhc nhms (contains dairy, fish)
            trh fvjkl sbzzf mxmxvkd (contains dairy)
            sqjhc fvjkl (contains soy)
            sqjhc mxmxvkd sbzzf (contains fish)
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield 'mxmxvkd,sqjhc,fvjkl' => <<<'INPUT'
            mxmxvkd kfcds sqjhc nhms (contains dairy, fish)
            trh fvjkl sbzzf mxmxvkd (contains dairy)
            sqjhc fvjkl (contains soy)
            sqjhc mxmxvkd sbzzf (contains fish)
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $ingredients = $this->parseIngredientAllergens($input);

        $sum = 0;

        foreach ($ingredients as $ingredient) {
            if ($ingredient['allergen'] === null) {
                $sum += $ingredient['count'];
            }
        }

        return (string) $sum;
    }

    public function solvePart2(string $input): string
    {
        $ingredients = $this->parseIngredientAllergens($input);

        $ingredients = array_filter($ingredients, static fn ($i): bool => $i['allergen'] !== null);
        uasort($ingredients, static fn ($a, $b): int => $a['allergen'] <=> $b['allergen']);

        return implode(',', array_keys($ingredients));
    }

    private function parseIngredientAllergens(string $input): array
    {
        $list = explode("\n", $input);
        $allergensFoods = [];
        $ingredients = [];

        foreach ($list as $food) {
            [$foodIngredients, $possibleAllergens] = explode(' (contains ', rtrim($food, ')'));
            $foodIngredients = explode(' ', $foodIngredients);
            $possibleAllergens = explode(', ', $possibleAllergens);

            foreach ($foodIngredients as $ingredient) {
                $ingredients[$ingredient] = [
                    'count' => ($ingredients[$ingredient]['count'] ?? 0) + 1,
                    'allergen' => null
                ];
            }

            foreach ($possibleAllergens as $possibleAllergen) {
                $allergensFoods[$possibleAllergen] = array_intersect(
                    $allergensFoods[$possibleAllergen] ?? $foodIngredients,
                    $foodIngredients
                );
            }
        }

        do {
            $change = false;

            foreach ($allergensFoods as $allergen => $possibleFoods) {
                if (count($possibleFoods) === 1) {
                    $ingredients[$possibleFoods[array_key_first($possibleFoods)]]['allergen'] = $allergen;
                    unset($allergensFoods[$allergen]);
                    $change = true;
                }
            }

            foreach ($allergensFoods as $allergen => $possibleFoods) {
                foreach ($possibleFoods as $index => $possibleFood) {
                    if ($ingredients[$possibleFood]['allergen'] !== null) {
                        unset($allergensFoods[$allergen][$index]);
                    }
                }
            }
        } while ($change);

        return $ingredients;
    }
}
