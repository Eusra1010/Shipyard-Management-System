<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title'       => 'NavalForge Achieves ISO 9001:2015 Recertification',
                'description' => 'After a rigorous third-party audit, NavalForge has been recertified under ISO 9001:2015 quality management standards. The certification covers all repair, dry-docking, and structural welding operations across our Chittagong facility.',
                'published_at'=> '2026-07-10',
            ],
            [
                'title'       => 'MV Padma Express Delivered After Full Overhaul',
                'description' => 'NavalForge successfully completed a comprehensive overhaul of MV Padma Express, including hull plating, main engine reconditioning, and electrical system upgrades, ahead of the contractual deadline.',
                'published_at'=> '2026-07-01',
            ],
            [
                'title'       => 'Expansion of Dry Dock Capacity Underway',
                'description' => 'Construction has commenced on two new berths at our Chittagong facility, increasing our simultaneous capacity to twelve vessels. The expansion is scheduled for completion in Q1 2027.',
                'published_at'=> '2026-06-18',
            ],
            [
                'title'       => 'Partnership Signed with Singapore Marine Inspection Agency',
                'description' => 'NavalForge has entered into a strategic inspection and classification partnership with a Singapore-based marine inspection agency, enabling our clients to access international survey services on-site.',
                'published_at'=> '2026-06-05',
            ],
            [
                'title'       => 'Zero Lost-Time Injury Milestone for 2025',
                'description' => 'Our workforce of over 200 technicians completed the full calendar year 2025 with zero lost-time injuries, reinforcing NavalForge\'s commitment to safety-first operations across all berths and workshops.',
                'published_at'=> '2026-05-20',
            ],
            [
                'title'       => 'New Welding Workshop Opens, Creating 45 Specialist Positions',
                'description' => 'A dedicated underwater and structural welding workshop has been inaugurated at our facility, equipped with hyperbaric welding units. Forty-five positions have been filled from local technical institutes in Chittagong.',
                'published_at'=> '2026-05-02',
            ],
            [
                'title'       => 'NavalForge Joins Bangladesh Ship Recycling Association',
                'description' => 'NavalForge has become a full member of the Bangladesh Ship Recycling Association, aligning our operations with the Hong Kong International Convention standards for safe and environmentally sound ship recycling.',
                'published_at'=> '2026-04-14',
            ],
        ];

        foreach ($items as $i => $item) {
            $id = $i + 1;
            DB::insert("
                INSERT INTO news (id, title, description, published_at, created_at, updated_at)
                VALUES (:id, :title, :description, TO_DATE(:pub, 'YYYY-MM-DD'), SYSDATE, SYSDATE)
            ", [
                'id'          => $id,
                'title'       => $item['title'],
                'description' => $item['description'],
                'pub'         => $item['published_at'],
            ]);
        }
    }
}
