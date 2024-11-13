@extends('layouts.dashbordlayout')

@section('content')
    <nav class="navbar">
        <div class="navbar-brand">
            <i class="fas fa-bars" id="menu-toggle"></i> Trash
        </div>
        <a href="{{ route('refresh-mails') }}" style="text-decoration: none; color: inherit;">
            <i class="fas fa-sync-alt fa-2x" style="color: white; float: right; margin-top: -5px;" aria-hidden="true"></i>
        </a>
    </nav>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <ul id="email-list" class="container email-list">
        @include('partials.email_items', ['mails' => $mails])
    </ul>

    <!-- Sentinel Element to detect when it's in view -->
    <div id="sentinel" style="height: 10px; background: transparent;"></div>

    <!-- Loading Indicator (Optional) -->
    <div id="loading" style="display:none;">Loading...</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let page = 1; // Track the current page
    let loading = false; // Prevent multiple AJAX calls
    let noMoreEmails = false; // Flag to prevent further requests when no emails are left

    // Function to load more emails
    function loadMoreEmails() {
        console.log("loadMoreEmails called");

        // Prevent loading if there are no more emails or if already loading
        if (loading || noMoreEmails) return;

        loading = true; // Set the loading flag to true
        $("#loading").show(); // Show loading indicator

        // Increment the page number to fetch the next set of emails
        page++;

        // AJAX request to load more emails
        $.ajax({
            url: "{{ route('trash.load') }}?page=" + page,
            type: "GET",
            success: function(data) {
                if (data.trim() !== "") {
                    $(".email-list").append(data); // Append new emails to the list
                } else {
                    noMoreEmails = true; // Set flag to stop further requests
                    $("#loading").text("No more emails").show(); // Display "No more emails" message
                }
            },
            complete: function() {
                loading = false; // Reset the loading flag
                $("#loading").hide(); // Hide loading indicator
            },
            error: function() {
                alert("Error loading more emails.");
            }
        });
    }

    // Set up the Intersection Observer to trigger when the sentinel element enters the viewport
    document.addEventListener("DOMContentLoaded", function() {
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Sentinel element is visible in the viewport, load more emails
                    loadMoreEmails();
                }
            });
        }, {
            rootMargin: "10px", // Trigger when 10px before the element reaches the viewport
            threshold: 1.0 // Only trigger when the sentinel is fully in view
        });

        // Target the sentinel element (an element at the bottom of the email list)
        const sentinel = document.querySelector("#sentinel");
        if (sentinel) {
            observer.observe(sentinel); // Start observing the sentinel element
        }
    });
</script>
