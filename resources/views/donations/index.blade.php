@extends('layouts.app')

@section('content')
<div class="container" style="margin-top:120px">
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('donations.create') }}" class="btn btn-md btn-primary shadow-sm border-0 mb-3">Send Donation</a>
            <div class="card border-0 rounded shadow-sm">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Amount</th>
                                <th>Notes</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($donations as $donation)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $donation->name }}</td>
                                <td>{{ $donation->email }}</td>
                                <td>{{ number_format($donation->amount, 0, ',', '.') }}</td>
                                <td>{{ $donation->note }}</td>
                                <td class="text-center">{{ $donation->status }}</td>
                                <td class="text-center">
                                    @if($donation->status == 'PENDING')
                                    <a href="{{ $donation->invoice_url }}" class="btn btn-sm btn-dark border-0 shadow-sm">Pay</a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <div class="alert alert-danger">
                                No donations found.
                            </div>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $donations->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection