<?php

namespace Database\Seeders;

use App\Domains\Core\Models\Category;
use App\Domains\Identity\Models\BusinessUnit;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buTI = BusinessUnit::with('customer')->where('slug', 'suporte-ti')->first();

        if ($buTI && $buTI->customer) {
            $categoriesTI = [
                ['name' => 'Hardware', 'description' => 'Problemas com monitor, mouse, teclado, notebook.'],
                ['name' => 'Software', 'description' => 'Instalação de programas, Office, VS Code.'],
                ['name' => 'Rede e Conectividade', 'description' => 'VPN, Wifi lento, Sem internet.'],
                ['name' => 'Acessos e Senhas', 'description' => 'Reset de senha, criação de e-mail, permissões.'],
            ];

            foreach ($categoriesTI as $cat) {
                Category::create([
                    'customer_id' => $buTI->customer_id,
                    'business_unit_id' => $buTI->id,
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                ]);
            }
        }

        $buMatriz = BusinessUnit::with('customer')->where('slug', 'matriz-hq')->first();

        if ($buMatriz && $buMatriz->customer) {
            $categoriesHQ = [
                ['name' => 'Infraestrutura Predial', 'description' => 'Ar condicionado, iluminação, limpeza.'],
                ['name' => 'Recursos Humanos', 'description' => 'Dúvidas de folha, benefícios, ponto.'],
                ['name' => 'Financeiro', 'description' => 'Reembolsos, notas fiscais.'],
            ];

            foreach ($categoriesHQ as $cat) {
                Category::create([
                    'customer_id' => $buMatriz->customer_id,
                    'business_unit_id' => $buMatriz->id,
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                ]);
            }
        }
    }
}
