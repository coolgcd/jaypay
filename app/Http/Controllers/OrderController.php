<?php
namespace App\Http\Controllers;

use App\Models\CartDetail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = CartDetail::where('sell_on', 'offline')
            ->where('joining', 'yes')
            ->orderBy('order_date', 'desc')
            ->paginate(25);

        return view('admin.orders.order', compact('orders'));
    }

    public function deleteOrder($orderId)
    {
        CartDetail::where('orderid', $orderId)->delete();
        return redirect()->back()->with('success', 'Order Deleted');
    }

    public function cancelOrder($orderId)
    {
        CartDetail::where('orderid', $orderId)->update(['status' => 4]);
        return redirect()->back()->with('success', 'Order Canceled');
    }

    public function updateOrders(Request $request)
    {
        if ($request->has('chkDelete')) {
            foreach ($request->chkDelete as $orderId) {
                if ($request->input('Process')) {
                    CartDetail::where('orderid', $orderId)->update(['status' => 2]);
                } elseif ($request->input('Confirm')) {
                    CartDetail::where('orderid', $orderId)->update(['status' => 3]);
                }
            }
            return redirect()->back()->with('success', 'Orders updated successfully');
        }

        return redirect()->back()->with('error', 'Please select at least one order');
    }

    public function manage()
    {
        $orders = CartDetail::with('cartPayment') // Assuming you have a relationship
            ->where('sell_on', 'offline')
            ->orderBy('order_date', 'desc')
            ->paginate(25); // Paginate results

        return view('admin.orders.repurchase_order', compact('orders'));
    }

    public function processOrders(Request $request)
    {
        $selectedOrders = $request->input('chkDelete', []);

        if (count($selectedOrders) > 0) {
            foreach ($selectedOrders as $orderId) {
                $order = CartDetail::find($orderId);
                if ($order) {
                    $order->update(['status' => 2]); // Set status to process
                }
            }
            return redirect()->back()->with('success', count($selectedOrders) . ' orders successfully transferred to process.');
        }

        return redirect()->back()->with('error', 'Please select orders to transfer for processing!');
    }

    public function manageOrder($id)
    {
        $order = CartDetail::find($id);
        if ($order) {
            $order->update(['status' => 4]); // Set status to canceled
            return redirect()->back()->with('success', 'Order canceled successfully.');
        }

        return redirect()->back()->with('error', 'Order not found!');
    }

    public function newdeleteOrder($id)
    {
        $order = CartDetail::find($id);
        if ($order) {
            $order->delete();
            // Delete related payment records if necessary
            CartPayment::where('orderid', $id)->delete();

            return redirect()->back()->with('success', 'Order deleted successfully.');
        }

        return redirect()->back()->with('error', 'Order not found!');
    }
}
