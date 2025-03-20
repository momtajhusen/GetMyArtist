@extends('ArtistPanel.artist-layout')

@section('styles')
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="https://demos.pixinvent.com/vuexy-html-admin-template/assets/img/favicon/favicon.ico" />

    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/dashboards-crm.js') }}"></script>
    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection

@section('content')
<div class="row g-6">

  <!-- Dashboard Overview Statistics -->
  <div class="col-lg-8 col-md-12">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <h5 class="card-title mb-0">Dashboard Overview</h5>
        <small class="text-muted">Updated 1 day ago</small>
      </div>
      <div class="card-body">
        <div class="row gy-3">
          <!-- Total Bookings -->
          <div class="col-md-3 col-6">
            <div class="d-flex align-items-center">
              <div class="badge rounded bg-label-primary me-4 p-2">
                <i class="ti ti-calendar-event ti-lg"></i>
              </div>
              <div class="card-info">
                <h5 class="mb-0">150</h5>
                <small>Total Bookings</small>
              </div>
            </div>
          </div>
          <!-- Pending Requests -->
          <div class="col-md-3 col-6">
            <div class="d-flex align-items-center">
              <div class="badge rounded bg-label-info me-4 p-2">
                <i class="ti ti-alert-circle ti-lg"></i>
              </div>
              <div class="card-info">
                <h5 class="mb-0">25</h5>
                <small>Pending Requests</small>
              </div>
            </div>
          </div>
          <!-- Earnings -->
          <div class="col-md-3 col-6">
            <div class="d-flex align-items-center">
              <div class="badge rounded bg-label-success me-4 p-2">
                <i class="ti ti-currency-dollar ti-lg"></i>
              </div>
              <div class="card-info">
                <h5 class="mb-0">$2,000</h5>
                <small>Earnings</small>
              </div>
            </div>
          </div>
          <!-- Upcoming Events -->
          <div class="col-md-3 col-6">
            <div class="d-flex align-items-center">
              <div class="badge rounded bg-label-warning me-4 p-2">
                <i class="ti ti-briefcase ti-lg"></i>
              </div>
              <div class="card-info">
                <h5 class="mb-0">3</h5>
                <small>Upcoming Events</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Booking Requests Section -->
  <div class="col-lg-4 col-md-12">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <h5 class="card-title mb-0">Booking Requests</h5>
        <small class="text-muted">Last 7 days</small>
      </div>
      <div class="card-body">
        <ul class="list-group">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Event A - 01 Apr 2025
            <span class="badge bg-label-info rounded-pill">Pending</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Event B - 05 Apr 2025
            <span class="badge bg-label-success rounded-pill">Confirmed</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Event C - 10 Apr 2025
            <span class="badge bg-label-warning rounded-pill">Reschedule</span>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Earnings Overview Chart -->
  <div class="col-xl-6">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="card-title">Earnings Overview</h5>
        <p class="card-subtitle">Monthly Revenue from Bookings</p>
      </div>
      <div class="card-body">
        <div id="earningsChart"></div>
      </div>
    </div>
  </div>

  <!-- Upcoming Bookings List -->
  <div class="col-xl-6">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="card-title">Upcoming Bookings</h5>
        <p class="card-subtitle">Your next events schedule</p>
      </div>
      <div class="card-body">
        <ul class="list-group">
          <li class="list-group-item">
            <div class="d-flex justify-content-between">
              <span>Wedding Event - 12 Apr 2025</span>
              <span class="badge bg-label-primary">Confirmed</span>
            </div>
          </li>
          <li class="list-group-item">
            <div class="d-flex justify-content-between">
              <span>Corporate Event - 20 Apr 2025</span>
              <span class="badge bg-label-info">Pending</span>
            </div>
          </li>
          <li class="list-group-item">
            <div class="d-flex justify-content-between">
              <span>Birthday Party - 25 Apr 2025</span>
              <span class="badge bg-label-success">Confirmed</span>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Reviews & Ratings Section -->
  <div class="col-xl-6">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <h5 class="card-title">Reviews & Ratings</h5>
        <small class="text-muted">Last Month</small>
      </div>
      <div class="card-body">
        <div id="reviewsChart"></div>
      </div>
    </div>
  </div>

  <!-- Activity Timeline -->
  <div class="col-xl-6">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <h5 class="card-title">Activity Timeline</h5>
        <small class="text-muted">Recent Booking Activity</small>
      </div>
      <div class="card-body">
        <ul class="timeline mb-0">
          <li class="timeline-item timeline-item-transparent">
            <span class="timeline-point timeline-point-primary"></span>
            <div class="timeline-event">
              <div class="timeline-header mb-2">
                <h6 class="mb-0">New Booking Confirmed</h6>
                <small class="text-muted">30 min ago</small>
              </div>
              <p class="mb-0">Booking for Corporate Event confirmed.</p>
            </div>
          </li>
          <li class="timeline-item timeline-item-transparent">
            <span class="timeline-point timeline-point-success"></span>
            <div class="timeline-event">
              <div class="timeline-header mb-2">
                <h6 class="mb-0">Booking Request Received</h6>
                <small class="text-muted">2 hrs ago</small>
              </div>
              <p class="mb-0">Received a booking request for Wedding Event.</p>
            </div>
          </li>
          <li class="timeline-item timeline-item-transparent">
            <span class="timeline-point timeline-point-info"></span>
            <div class="timeline-event">
              <div class="timeline-header mb-2">
                <h6 class="mb-0">Payment Processed</h6>
                <small class="text-muted">1 day ago</small>
              </div>
              <p class="mb-0">Payment for Birthday Party completed.</p>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>

</div>
@endsection
