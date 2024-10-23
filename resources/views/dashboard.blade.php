@php
use App\Models\Medication;
use App\Models\Prescription;
use App\Models\Inventory;
use App\Models\Organization;
use Carbon\Carbon;

class DashboardData
{
    private $user;
    private $organization;

    public function __construct($user)
    {
        $this->user = $user;
        $this->organization = $user->organization;
    }

    public function getTotalInventoryItems()
    {
        return $this->organization->inventories()->count();
    }

    public function getLowStockItems()
    {
        return $this->organization->inventories()->where('quantity', '<=', 10)->count();
    }

    public function getPrescriptionsToday()
    {
        return $this->organization->prescriptions()->whereDate('created_at', Carbon::today())->count();
    }

    public function getInventoryTrend()
    {
        $lastMonth = Carbon::now()->subMonth();
        $currentCount = $this->organization->inventories()->count();
        $lastMonthCount = $this->organization->inventories()->where('created_at', '<', $lastMonth)->count();

        $percentageChange = $lastMonthCount > 0
            ? (($currentCount - $lastMonthCount) / $lastMonthCount) * 100
            : 100;

        return [
            'current' => $currentCount,
            'percentage' => round($percentageChange, 1),
            'trend' => $percentageChange >= 0 ? 'up' : 'down'
        ];
    }

    public function getRecentActivity($limit = 5)
    {
        $activities = collect();

        if ($this->user->can('view medications')) {
            $medications = $this->organization->inventories()->with('medication')->latest()->take($limit)->get()->map(function ($inventory) {
                return [
                    'type' => 'medication',
                    'message' => "Inventory updated for: {$inventory->medication->name}",
                    'date' => $inventory->updated_at
                ];
            });
            $activities = $activities->concat($medications);
        }

        if ($this->user->can('view prescriptions')) {
            $prescriptions = $this->organization->prescriptions()->with('user')->latest()->take($limit)->get()->map(function ($prescription) {
                return [
                    'type' => 'prescription',
                    'message' => "Prescription filled for: {$prescription->patient_name}",
                    'user' => $prescription->user->name,
                    'date' => $prescription->created_at
                ];
            });
            $activities = $activities->concat($prescriptions);
        }

        return $activities->sortByDesc('date')->take($limit);
    }

    public function getExpiringMedications()
    {
        $threeMonthsFromNow = now()->addMonths(3);
        return $this->organization->inventories()
            ->where('expiration_date', '<=', $threeMonthsFromNow)
            ->where('expiration_date', '>', now())
            ->count();
    }
}

// Fetch data for the dashboard
$dashboardData = new DashboardData(auth()->user());
$inventoryTrend = $dashboardData->getInventoryTrend();
$recentActivities = $dashboardData->getRecentActivity();

$expiringProducts = $dashboardData->getExpiringMedications()

@endphp

<x-app-layout>
    <div>
        <!-- Main Content -->
        <div class="ml-0 transition-all duration-300 ease-in-out">
            <!-- Page Content -->
            <main class="py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="px-4 py-6 sm:px-0">
                    <h2 class="mb-6 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                        Welcome, {{ auth()->user()->name }} | Organization: {{ auth()->user()->organization->name }}
                    </h2>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        @can('view inventory')
                        <!-- Inventory Overview Card -->
                        <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 p-3 bg-blue-500 rounded-md">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 w-0 ml-5">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                                Total Inventory Items
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900 dark:text-gray-200">
                                                    {{ $inventoryTrend['current'] }}
                                                </div>
                                                <div class="ml-2 flex items-baseline text-sm font-semibold {{ $inventoryTrend['trend'] === 'up' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                    <svg class="self-center flex-shrink-0 h-5 w-5 {{ $inventoryTrend['trend'] === 'up' ? 'text-green-500 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="{{ $inventoryTrend['trend'] === 'up' ? 'M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z' : 'M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z' }}" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="sr-only">{{ $inventoryTrend['trend'] === 'up' ? 'Increased' : 'Decreased' }} by</span>
                                                    {{ abs($inventoryTrend['percentage']) }}%
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Low Stock Alert Card -->
                        <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 p-3 bg-red-500 rounded-md">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 w-0 ml-5">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                                Low Stock Items
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900 dark:text-gray-200">
                                                    {{ $dashboardData->getLowStockItems() }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 p-3 bg-yellow-500 rounded-md">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 w-0 ml-5">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                                Medications Close to Expiry
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900 dark:text-gray-200">
                                                    {{ $expiringProducts }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700">
                                <div class="text-sm">
                                    <a href="{{ route('medications.expiring', ['organization' => auth()->user()->organization]) }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">View all</a>
                                </div>
                            </div>
                        </div>

                        @endcan

                        @can('view prescriptions')
                        <!-- Prescriptions Card -->
                        <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 p-3 bg-green-500 rounded-md">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 w-0 ml-5">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                                Prescriptions Today
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900 dark:text-gray-200">
                                                    {{ $dashboardData->getPrescriptionsToday() }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700">
                                <div class="text-sm">
                                    <a href="{{ route('prescriptions.index') }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">View all</a>
                                </div>
                            </div>
                        </div>
                        @endcan

                        @if(auth()->user()->hasRole('super-admin'))
                        <!-- Organizations Card (Only for super-admin) -->
                        <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 p-3 bg-purple-500 rounded-md">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 w-0 ml-5">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                                Total Organizations
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900 dark:text-gray-200">
                                                    {{ Organization::count() }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700">
                                <div class="text-sm">
                                    <a href="{{ route('organizations.index') }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">View all</a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Recent Activity -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-200">Recent Activity</h3>
                        <div class="mt-2 overflow-hidden bg-white shadow dark:bg-gray-800 sm:rounded-md">
                            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($recentActivities as $activity)
                                    <li>
                                        <a href="#" class="block hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <div class="px-4 py-4 sm:px-6">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-sm font-medium text-blue-600 truncate dark:text-blue-400">
                                                        {{ $activity['message'] }}

                                                    </p>
                                                    <div class="flex flex-shrink-0 ml-2">
                                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $activity['type'] === 'medication' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' }}">
                                                            {{ ucfirst($activity['type']) }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="mt-2 sm:flex sm:justify-between">
                                                    <div class="sm:flex">
                                                        <p class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                            </svg>
                                                            {{ $activity['user'] ?? 'System' }}
                                                        </p>
                                                    </div>
                                                    <div class="flex items-center mt-2 text-sm text-gray-500 dark:text-gray-400 sm:mt-0">
                                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                                        </svg>
                                                        <p>
                                                            <time datetime="{{ $activity['date']->toIso8601String() }}">{{ $activity['date']->format('F j, Y') }}</time>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>
