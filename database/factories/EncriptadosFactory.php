<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Encriptados;
use App\Models\User;
use App\Services\EncryptionService;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Encriptados>
 */
class EncriptadosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Encriptados::class;


    public function definition(): array
    {
        $encryptionService = new EncryptionService();
        $clave = $encryptionService->generarKey();
        $content = $this->faker->text(maxNbChars: 64);
        $encryptedContent = $encryptionService->encriptar($content, $clave);
        return [
            'user_id' => User::factory(),
            'content' => $encryptedContent,
            'key' => $clave,
        ];
    }
}
