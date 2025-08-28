@extends('member.layout')
@section('content')
<style>
    /* .py-4{
        margin-left: 130px;
    } */
    .service-card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
        cursor: pointer;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .service-card.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        transform: translateY(-2px);
    }
    
    .service-card.active:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
    }
    
    .service-card.inactive {
        background: #f8f9fa;
        color: #6c757d;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .service-card.inactive:hover {
        background: #e9ecef;
        transform: translateY(-1px);
    }
    
    .service-icon {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }
    
    .service-card.active .service-icon {
        color: #ffffff;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .service-card.inactive .service-icon {
        color: #adb5bd;
    }
    
    .service-name {
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0;
    }
    
    .service-card.active .service-name {
        color: #ffffff;
    }
    
    .service-card.inactive .service-name {
        color: #6c757d;
    }
    
    .page-title {
        color: #495057;
        font-weight: 600;
        margin-bottom: 2rem;
    }
    
    .services-grid {
        gap: 1rem;
        margin-left: 120px;
          margin-top: 50px;
    }
    
    /* Mobile First - Extra Small devices (portrait phones, less than 576px) */
    @media (max-width: 575.98px) {
        .service-card {
            height: 90px;
        }
        
        .service-icon {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }
        
        .service-name {
            font-size: 0.75rem;
        }
        .services-grid{
            margin-left: 80px;
          
        }
    }
    
    /* Small devices (landscape phones, 576px and up) */
    @media (min-width: 576px) and (max-width: 767.98px) {
        .service-card {
            height: 100px;
        }
        
        .service-icon {
            font-size: 2rem;
        }
        
        .service-name {
            font-size: 0.8rem;
        }
    }
    
    /* Medium devices (tablets, 768px and up) */
    @media (min-width: 768px) and (max-width: 991.98px) {

       
        .service-card {
            height: 110px;
        }
        
        .service-icon {
            font-size: 2.2rem;
        }
        
        .service-name {
            font-size: 0.85rem;
        }

    }
    .service-card.inactive {
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
}

    
    /* Large devices (desktops, 992px and up) */
    @media (min-width: 992px) and (max-width: 1199.98px) {
        .service-card {
            height: 120px;
        }
        
        .service-icon {
            font-size: 2.5rem;
        }
        
        .service-name {
            font-size: 0.9rem;
        }
    }
    
    /* Extra large devices (large desktops, 1200px and up) */
    @media (min-width: 1200px) {
        .service-card {
            height: 130px;
        }
        
        .service-icon {
            font-size: 2.8rem;
        }
        
        .service-name {
            font-size: 1rem;
        }
    }
</style>
            <!-- ['name' => 'Mobile Recharge', 'icon' => 'mobile-alt', 'active' => true, 'route' => route('member.recharge.mobile')], -->

<div class="container py-4">
    <h4 class="page-title mb-4">Recharge & Utility Services</h4>
    <div class="row services-grid text-center">
        @php
        $services = [
            ['name' => 'Mobile Recharge', 'icon' => 'mobile-alt', 'active' => true, 'route' => route('member.recharge.mobile')],
            ['name' => 'DTH', 'icon' => 'satellite-dish', 'active' => false],
            ['name' => 'FASTag', 'icon' => 'car', 'active' => false],
            ['name' => 'Electricity', 'icon' => 'bolt', 'active' => false],
            ['name' => 'Gas Bill', 'icon' => 'fire', 'active' => false],
            ['name' => 'Piped Gas', 'icon' => 'burn', 'active' => false],
        ];
        @endphp
        
        @foreach ($services as $service)
        <div class="col-6 col-sm-4 col-lg-4 col-xl-3 mb-3">
            @if($service['active'] && isset($service['route']))
                <a href="{{ $service['route'] }}" class="text-decoration-none">
            @endif
            @if(!$service['active'])
    <span class="badge bg-secondary mt-2">Coming Soon</span>
@endif

            <div class="card service-card {{ $service['active'] ? 'active' : 'inactive' }}">
                <div class="card-body text-center">
                    <i class="fas fa-{{ $service['icon'] }} service-icon"></i>
                    <h6 class="service-name">{{ $service['name'] }}</h6>
                </div>
            </div>

            @if($service['active'] && isset($service['route']))
                </a>
            @endif
        </div>
        @endforeach
    </div>
</div>

@endsection