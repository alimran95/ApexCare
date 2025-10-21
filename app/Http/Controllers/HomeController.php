<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\Hospital;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $specialties = Specialty::all();
        $cities = Doctor::select('city')
            ->distinct()
            ->whereNotNull('city')
            ->pluck('city');

        // Get doctors for find doctor section
        $doctors = Doctor::with(['user', 'specialties'])
            ->whereHas('user', function($q) {
                $q->where('is_active', true);
            })
            ->limit(6) // Show only 6 on home page
            ->get();

        // Get clinics for find clinics section
        $clinics = Clinic::limit(6)->get(); // Show only 6 on home page
        $clinicCities = Clinic::select('address')
            ->distinct()
            ->whereNotNull('address')
            ->get()
            ->map(function($clinic) {
                // Extract city from address (assuming last part is city)
                $parts = explode(',', $clinic->address);
                return trim(end($parts));
            })
            ->unique()
            ->values();

        // Get health tips for health tips section
        $healthTips = [
            // General Health Tips
            [
                'title' => 'Stay Hydrated',
                'category' => 'General Health',
                'content' => 'Drink at least 8 glasses of water daily to maintain proper hydration and support overall health.',
                'image' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(1)
            ],
            [
                'title' => 'Regular Check-ups',
                'category' => 'General Health',
                'content' => 'Schedule regular health check-ups and screenings to detect potential health issues early.',
                'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(6)
            ],
            [
                'title' => 'Hand Hygiene',
                'category' => 'General Health',
                'content' => 'Wash your hands frequently with soap for at least 20 seconds to prevent the spread of germs and infections.',
                'image' => 'https://images.unsplash.com/photo-1584362917165-526a968579e8?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(8)
            ],
            
            // Fitness Tips
            [
                'title' => 'Regular Exercise',
                'category' => 'Fitness',
                'content' => 'Aim for at least 30 minutes of moderate exercise daily to improve cardiovascular health and maintain a healthy weight.',
                'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(2)
            ],
            [
                'title' => 'Strength Training',
                'category' => 'Fitness',
                'content' => 'Include strength training exercises 2-3 times per week to build muscle mass and improve bone density.',
                'image' => 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(7)
            ],
            [
                'title' => 'Daily Walking',
                'category' => 'Fitness',
                'content' => 'Take at least 10,000 steps daily. Walking is a simple yet effective way to stay active and healthy.',
                'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(15)
            ],
            
            // Nutrition Tips
            [
                'title' => 'Balanced Diet',
                'category' => 'Nutrition',
                'content' => 'Include a variety of fruits, vegetables, whole grains, and lean proteins in your daily meals.',
                'image' => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(3)
            ],
            [
                'title' => 'Portion Control',
                'category' => 'Nutrition',
                'content' => 'Use smaller plates and bowls to help control portion sizes and prevent overeating.',
                'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(9)
            ],
            
            // Mental Health Tips  
            [
                'title' => 'Stress Management',
                'category' => 'Mental Health',
                'content' => 'Practice relaxation techniques like deep breathing, meditation, or yoga to manage stress effectively.',
                'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(5)
            ],
            [
                'title' => 'Quality Sleep',
                'category' => 'Mental Health',
                'content' => 'Get 7-9 hours of quality sleep each night to support mental health and physical recovery.',
                'image' => 'https://images.unsplash.com/photo-1540573133985-87b6da6d54a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(4)
            ],
            [
                'title' => 'Social Connections',
                'category' => 'Mental Health',
                'content' => 'Maintain strong social relationships and stay connected with family and friends for emotional well-being.',
                'image' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(13)
            ]
        ];

        return view('home', compact('specialties', 'cities', 'doctors', 'clinics', 'clinicCities', 'healthTips'));
    }

    public function search(Request $request)
    {
        $searchType = $request->input('type', 'doctor');
        $query = $request->input('query', '');
        $specialty = $request->input('specialty');
        $city = $request->input('city');

        $results = [];

        if ($searchType === 'doctor') {
            $results = Doctor::with('specialties')
                ->when($query, function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })
                ->when($specialty, function($q) use ($specialty) {
                    $q->whereHas('specialties', function($q) use ($specialty) {
                        $q->where('specialties.id', $specialty);
                    });
                })
                ->when($city, function($q) use ($city) {
                    $q->where('city', $city);
                })
                ->limit(10)
                ->get();
        } else {
            $results = Hospital::query()
                ->when($query, function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })
                ->when($city, function($q) use ($city) {
                    $q->where('city', $city);
                })
                ->limit(10)
                ->get();
        }

        return response()->json([
            'results' => $results,
            'type' => $searchType
        ]);
    }

    /**
     * Show the Find Doctor page
     */
    public function findDoctor()
    {
        $specialties = Specialty::all();
        $doctors = Doctor::with(['user', 'specialties'])
            ->whereHas('user', function($q) {
                $q->where('is_active', true);
            })
            ->paginate(12);
        $cities = Doctor::select('city')
            ->distinct()
            ->whereNotNull('city')
            ->pluck('city');

        return view('pages.find-doctor', compact('doctors', 'specialties', 'cities'));
    }

    /**
     * Show the Find Clinics page
     */
    public function findClinics()
    {
        $clinics = Clinic::paginate(12);
        $cities = Clinic::select('address')
            ->distinct()
            ->whereNotNull('address')
            ->get()
            ->map(function($clinic) {
                // Extract city from address (assuming last part is city)
                $parts = explode(',', $clinic->address);
                return trim(end($parts));
            })
            ->unique()
            ->values();

        return view('pages.find-clinics', compact('clinics', 'cities'));
    }

    /**
     * Show the Health Tips page
     */
    public function healthTips()
    {
        // Extended health tips with more diverse content for better filtering
        $healthTips = [
            // General Health Tips
            [
                'title' => 'Stay Hydrated',
                'category' => 'General Health',
                'content' => 'Drink at least 8 glasses of water daily to maintain proper hydration and support overall health.',
                'image' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(1)
            ],
            [
                'title' => 'Regular Check-ups',
                'category' => 'General Health',
                'content' => 'Schedule regular health check-ups and screenings to detect potential health issues early.',
                'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(6)
            ],
            [
                'title' => 'Hand Hygiene',
                'category' => 'General Health',
                'content' => 'Wash your hands frequently with soap for at least 20 seconds to prevent the spread of germs and infections.',
                'image' => 'https://images.unsplash.com/photo-1584362917165-526a968579e8?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(8)
            ],
            [
                'title' => 'Sun Protection',
                'category' => 'General Health',
                'content' => 'Use sunscreen with SPF 30+ and wear protective clothing when outdoors to prevent skin damage.',
                'image' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(10)
            ],
            
            // Fitness Tips
            [
                'title' => 'Regular Exercise',
                'category' => 'Fitness',
                'content' => 'Aim for at least 30 minutes of moderate exercise daily to improve cardiovascular health and maintain a healthy weight.',
                'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(2)
            ],
            [
                'title' => 'Strength Training',
                'category' => 'Fitness',
                'content' => 'Include strength training exercises 2-3 times per week to build muscle mass and improve bone density.',
                'image' => 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(7)
            ],
            [
                'title' => 'Flexibility & Stretching',
                'category' => 'Fitness',
                'content' => 'Incorporate stretching and flexibility exercises to improve range of motion and prevent injuries.',
                'image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(12)
            ],
            [
                'title' => 'Daily Walking',
                'category' => 'Fitness',
                'content' => 'Take at least 10,000 steps daily. Walking is a simple yet effective way to stay active and healthy.',
                'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(15)
            ],
            
            // Nutrition Tips
            [
                'title' => 'Balanced Diet',
                'category' => 'Nutrition',
                'content' => 'Include a variety of fruits, vegetables, whole grains, and lean proteins in your daily meals.',
                'image' => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(3)
            ],
            [
                'title' => 'Portion Control',
                'category' => 'Nutrition',
                'content' => 'Use smaller plates and bowls to help control portion sizes and prevent overeating.',
                'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(9)
            ],
            [
                'title' => 'Limit Processed Foods',
                'category' => 'Nutrition',
                'content' => 'Reduce consumption of processed foods high in sugar, salt, and unhealthy fats.',
                'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(11)
            ],
            [
                'title' => 'Healthy Snacking',
                'category' => 'Nutrition',
                'content' => 'Choose nutritious snacks like nuts, fruits, or yogurt instead of chips and candy.',
                'image' => 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(14)
            ],
            
            // Mental Health Tips
            [
                'title' => 'Stress Management',
                'category' => 'Mental Health',
                'content' => 'Practice relaxation techniques like deep breathing, meditation, or yoga to manage stress effectively.',
                'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(5)
            ],
            [
                'title' => 'Quality Sleep',
                'category' => 'Mental Health',
                'content' => 'Get 7-9 hours of quality sleep each night to support mental health and physical recovery.',
                'image' => 'https://images.unsplash.com/photo-1540573133985-87b6da6d54a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(4)
            ],
            [
                'title' => 'Social Connections',
                'category' => 'Mental Health',
                'content' => 'Maintain strong social relationships and stay connected with family and friends for emotional well-being.',
                'image' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(13)
            ],
            [
                'title' => 'Mindfulness Practice',
                'category' => 'Mental Health',
                'content' => 'Practice mindfulness and meditation to reduce anxiety and improve mental clarity.',
                'image' => 'https://images.unsplash.com/photo-1499209974431-9dddcece7f88?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
                'created_at' => now()->subDays(16)
            ]
        ];

        return view('pages.health-tips', compact('healthTips'));
    }
}
