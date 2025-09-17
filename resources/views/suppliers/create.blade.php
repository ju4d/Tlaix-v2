@extends('layouts.app')
@section('title','Add Supplier')
@section('content')
<form method="POST" action="{{ route('suppliers.store') }}">
    @csrf
    <div class="card">
        <h3>Supplier Information</h3>

        <label>Company Name *</label>
        <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g., Fresh Foods Co.">

        <label>Contact Person</label>
        <input type="text" name="contact" value="{{ old('contact') }}" placeholder="e.g., John Doe">

        <label>Phone Number</label>
        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="e.g., +52 33 1234 5678">

        <label>Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="e.g., orders@company.com">

        <div style="margin-top: 20px;">
            <button type="submit">💾 Create Supplier</button>
            <a href="{{ route('suppliers.index') }}" style="margin-left: 10px; color: #7f8c8d;">Cancel</a>
        </div>
    </div>
</form>
@endsection
