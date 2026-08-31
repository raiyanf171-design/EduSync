<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\BKashService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    protected $bkashService;

    public function __construct(BKashService $bkashService)
    {
        $this->bkashService = $bkashService;
    }

    /**
     * Display all subscriptions
     */
    public function index()
    {
        $subscriptions = Subscription::with('school', 'package')->paginate(15);
        return view('super-admin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * Show subscription details
     */
    public function show(Subscription $subscription)
    {
        return view('super-admin.subscriptions.show', compact('subscription'));
    }

    /**
     * Verify payment callback from bKash
     */
    public function verifyPayment(Request $request)
    {
        try {
            $paymentID = $request->input('paymentID');
            $result = $this->bkashService->executePayment($paymentID);

            if ($result['success']) {
                $subscription = Subscription::where('payment_id', $paymentID)->firstOrFail();
                $subscription->update([
                    'status' => 'completed',
                    'transaction_id' => $result['transactionID'],
                ]);

                $subscription->school->update(['status' => 'active']);

                return redirect()->route('super-admin.subscriptions.show', $subscription)
                    ->with('success', 'Payment verified successfully');
            }

            return back()->with('error', 'Payment verification failed');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Get revenue statistics
     */
    public function revenue()
    {
        $totalRevenue = Subscription::where('status', 'completed')->sum('amount');
        $monthlyRevenue = Subscription::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
        $activeSubscriptions = Subscription::where('status', 'completed')
            ->where('expire_date', '>', now())
            ->count();

        return view('super-admin.subscriptions.revenue', compact(
            'totalRevenue',
            'monthlyRevenue',
            'activeSubscriptions'
        ));
    }
}
