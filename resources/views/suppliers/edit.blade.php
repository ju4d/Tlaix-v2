@extends('layouts.app')
@section('title','Edit Supplier')
@section('content')
<form method="POST" action="{{ route('suppliers.update', $supplier->id) }}">
    @csrf @method('PUT')
    <div class="card">
        <h3>Edit Supplier: {{ $supplier->name }}</h3>

        <label>Company Name *</label>
        <input type="text" name="name" required value="{{ old('name', $supplier->name) }}">

        <label>Contact Person</label>
        <input type="text" name="contact" value="{{ old('contact', $supplier->contact) }}">

        <label>Phone Number</label>
        <input type="tel" name="phone" value="{{ old('phone', $supplier->phone) }}">

        <label>Email Address</label>
        <input type="email" name="email" value="{{ old('email', $supplier->email) }}">

        <div style="margin-top: 20px;">
            <button type="submit">💾 Update Supplier</button>
            <a href="{{ route('suppliers.index') }}" style="margin-left: 10px; color: #7f8c8d;">Cancel</a>
        </div>
    </div>
</form>
@endsection
