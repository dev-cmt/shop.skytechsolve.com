<x-backend-layout title="Orders Management">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Orders Management</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Orders</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Orders List</div>
                    <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm">
                        <i class="ri-add-line me-1 fw-semibold align-middle"></i>Create New Order
                    </a>
                </div>
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice No</th>
                                    <th>Customer</th>
                                    <th>Store</th>
                                    <th>Total</th>
                                    <th>Paid/Due</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $orderStatus = [
                                        1 => ['label' => 'Pending',    'color' => 'warning'],
                                        2 => ['label' => 'Processing', 'color' => 'info'],
                                        3 => ['label' => 'Shipped',    'color' => 'primary'],
                                        4 => ['label' => 'Delivered',  'color' => 'success'],
                                        5 => ['label' => 'Cancelled',  'color' => 'danger'],
                                    ];
                                @endphp
                                @forelse($orders as $key => $order)
                                    <tr>
                                        <td>{{ $orders->firstItem() + $key }}</td>
                                        <td><a href="{{ route('orders.edit', $order->id) }}" class="fw-bold text-primary">{{ $order->invoice_no }}</a></td>
                                        <td>
                                            <strong>{{ $order->customer_name }}</strong><br>
                                            <small class="text-muted">{{ $order->customer_phone }}</small>
                                        </td>
                                        <td>{{ $order->store->name ?? 'N/A' }}</td>
                                        <td>${{ number_format($order->total, 2) }}</td>
                                        <td>
                                            <span class="badge bg-success-transparent">Paid: ${{ number_format($order->paid, 2) }}</span><br>
                                            <span class="badge bg-danger-transparent">Due: ${{ number_format($order->due, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $orderStatus[$order->status]['color'] }}-transparent">
                                                {{ $orderStatus[$order->status]['label'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-list">
                                                <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-warning-light btn-icon" title="Edit">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger-light btn-icon" onclick="return confirm('Are you sure you want to delete this order and all its items?')" title="Delete">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No orders found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>