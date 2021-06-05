<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Exports\OrderExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\OrderDelivery;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersMultipleExport;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $orderCount = Order::whereHas('orderItems')->count();
        $dataPerPage = 2;
        $orderPages = ceil($orderCount / $dataPerPage);
        $currentPage = isset($request->all()['page']) ? $request->all()['page'] : 1;
        $orders = Order::with('user', 'orderItems.product')
            ->orderBy('created_at', 'desc')
            ->offset($dataPerPage * ($currentPage - 1))
            ->limit($dataPerPage)
            ->whereHas('orderItems')
            ->get();

        return view('admin.orders.index', [
            'orders' => $orders,
            'orderCount' => $orderCount,
            'orderPages' => $orderPages
        ]);
    }

    public function delivery($id)
    {
        $order = Order::find($id);
        if ($order->is_shipped) {
            return response(['result' => false]);
        } else {
            $order->update(['is_shipped' => true]);
            $order->user->notify(new OrderDelivery);
            return response(['result' => true]);
        }
    }

    public function export()
    {
        return Excel::download(new OrderExport(), 'orders.xlsx');
    }

    public function exportByShipped()
    {
        return Excel::download(new OrdersMultipleExport(), 'orders_by_shipped.xlsx');
    }
}
