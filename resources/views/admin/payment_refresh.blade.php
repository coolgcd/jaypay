@extends('admin.layout')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Manual Income Triggers</h4>
        </div>
        <div class="card-body">
            <div class="row g-3">

                <!-- Daily Income -->
                  <div class="col-md-6 col-xl-3">
                    <a target="_blank" href="{{ url('admin/calculate-daily-income') }}" class="btn btn-primary w-100">
                        <i class="fas fa-calendar-day me-1"></i> Run Daily Income
                    </a>
                </div>

                <!-- Sponsor Income -->
                <div class="col-md-6 col-xl-3">
                    <a target="_blank" href="{{ url('admin/direct-income') }}" class="btn btn-success w-100">
                        <i class="fas fa-user-friends me-1"></i> Run Sponsor Income
                    </a>
                </div>

                <!-- Salary Income -->
                <div class="col-md-6 col-xl-3">
                    <a target="_blank" href="{{ url('admin/salary-income') }}" class="btn btn-info text-white w-100">
                        <i class="fas fa-briefcase me-1"></i> Run Salary Income
                    </a>
                </div>

                <!-- Reward Income -->
                <div class="col-md-6 col-xl-3">
                    <a target="_blank" href="{{ url('admin/reward-income') }}" class="btn btn-warning w-100">
                        <i class="fas fa-award me-1"></i> Run Reward Bonus
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
