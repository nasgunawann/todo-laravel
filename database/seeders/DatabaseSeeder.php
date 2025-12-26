<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Todo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Test
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'nama' => 'Pengguna Test',
                'kata_sandi' => Hash::make('password'),
            ]
        );

        // 2. Buat Kategori
        $kategoriList = [
            [
                'nama' => 'Pekerjaan',
                'warna' => '#3b82f6', // Blue
                'ikon' => 'briefcase',
            ],
            [
                'nama' => 'Personal',
                'warna' => '#10b981', // Green
                'ikon' => 'user',
            ],
            [
                'nama' => 'Belajar',
                'warna' => '#f59e0b', // Orange
                'ikon' => 'book',
            ],
            [
                'nama' => 'Kesehatan',
                'warna' => '#ef4444', // Red
                'ikon' => 'heart',
            ],
            [
                'nama' => 'Belanja',
                'warna' => '#8b5cf6', // Purple
                'ikon' => 'shopping-cart',
            ],
        ];

        $kategoris = [];
        foreach ($kategoriList as $k) {
            $kategoris[] = Kategori::firstOrCreate(
                [
                    'pengguna_id' => $user->id,
                    'nama' => $k['nama']
                ],
                $k
            );
        }

        // 3. Buat 50 Todo
        $actions = ['Membuat', 'Review', 'Refactor', 'Meeting dengan', 'Beli', 'Perbaiki', 'Analisa', 'Update'];
        $subjects = ['Laporan', 'Kode API', 'Desain UI', 'Client', 'Bahan Makanan', 'Bug Login', 'Data User', 'Dokumentasi'];
        $priorities = ['rendah', 'sedang', 'tinggi'];
        $statuses = ['tertunda', 'sedang_dikerjakan', 'selesai'];

        for ($i = 0; $i < 50; $i++) {
            $action = $actions[array_rand($actions)];
            $subject = $subjects[array_rand($subjects)];
            $priority = $priorities[array_rand($priorities)];
            $status = $statuses[array_rand($statuses)];
            $kategori = $kategoris[array_rand($kategoris)];

            // Random date: -5 to +10 days
            $days = rand(-5, 10);
            $deadline = now()->addDays($days)->setHour(rand(8, 20))->setMinute(0);

            // Jika status selesai, set date selesai
            $completedAt = $status === 'selesai' ? now() : null;

            // Jika status selesai, override deadline kadang2 biar ada history late

            Todo::create([
                'pengguna_id' => $user->id,
                'kategori_id' => $kategori->id,
                'judul' => "$action $subject " . ($i + 1),
                'deskripsi' => "Ini adalah deskripsi dummy untuk tugas $action $subject. Pastikan dikerjakan dengan teliti.",
                'prioritas' => $priority,
                'status' => $status,
                'tenggat_waktu' => $deadline,
                'disematkan' => rand(0, 10) > 8, // 20% chance pinned
                'diselesaikan_pada' => $completedAt,
            ]);
        }
    }
}
