<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phase Plan Form - Gmail Theme</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/core.css') }}" class="template-customizer-core-css" />
</head>
<body>

<!-- Gmail-like Red Navbar -->
<nav class="navbar">
    <a class="navbar-brand" href="#">
        <i class="fas fa-arrow-left"></i> Phase Plan
    </a>
</nav>

<!-- Form Container -->
<div class="container">
    <div class="form-header">Phase Plan Form</div>
    <form>
        <!-- Plan Information Section -->
        <div class="section-title">Plan Information</div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="planStart">Plan Start</label>
                    <input type="datetime-local" id="planStart" value="2024-09-11T01:25:00">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="planAmount">Plan Amount</label>
                    <div class="d-flex">
                        <input type="text" id="planAmount" value="5754">
                        <select id="unit" class="ms-2">
                            <option value="word">Word</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Working Days Section -->
        <div class="section-title">Working Days</div>
        <div class="form-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="sun" checked>
                <label class="form-check-label" for="sun">Sun</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="mon" checked>
                <label class="form-check-label" for="mon">Mon</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="tue" checked>
                <label class="form-check-label" for="tue">Tue</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="wed" checked>
                <label class="form-check-label" for="wed">Wed</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="thu">
                <label class="form-check-label" for="thu">Thu</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="fri">
                <label class="form-check-label" for="fri">Fri</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="sat">
                <label class="form-check-label" for="sat">Sat</label>
            </div>
        </div>

        <!-- Phase Information Section -->
        <div class="section-title">Phase Information</div>
        <div class="toggle-group">
            <button type="button" class="btn btn-active" id="translationBtn">Translation</button>
            <button type="button" class="btn" id="revisionBtn">Revision</button>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phaseStart">Phase Start</label>
                    <input type="datetime-local" id="phaseStart" value="2024-09-11T01:25:00">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phaseEnd">Phase End</label>
                    <input type="datetime-local" id="phaseEnd" value="2024-09-12T05:21:59">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="maxPrice">Max Price</label>
                    <input type="text" id="maxPrice" placeholder="Enter max price">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <div class="d-flex">
                        <input type="number" id="amount" value="5754">
                        <select id="unit" class="ms-2">
                            <option value="word">Word</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selection Plan Section -->
        <div class="section-title">Selection Plan</div>
        <div class="form-group">
            <label for="selectionPlan">Selection Plan</label>
            <select id="selectionPlan">
                <option value="">Please Select..</option>
                <!-- More options can be added -->
            </select>
        </div>

        <!-- Shared Phase Instructions -->
        <div class="form-group">
            <label for="sharedPhaseInstructions">Shared Phase Instructions</label>
            <textarea id="sharedPhaseInstructions" class="rich-text-editor" placeholder="Enter any special instructions..."></textarea>
        </div>

        <!-- Translation Process and Application Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="translationProcess">Translation Process</label>
                    <select id="translationProcess">
                        <option value="translation_only">Translation Only</option>
                        <!-- More options can be added -->
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="translationApplication">Translation Application</label>
                    <select id="translationApplication">
                        <option value="sdl_trados_studio">SDL Trados Studio</option>
                        <!-- More options can be added -->
                    </select>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="form-group text-center">
            <button type="button" class="btn-submit">Done</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle between Translation and Revision buttons
    const translationBtn = document.getElementById('translationBtn');
    const revisionBtn = document.getElementById('revisionBtn');

    translationBtn.addEventListener('click', function() {
        translationBtn.classList.add('btn-active');
        revisionBtn.classList.remove('btn-active');
    });

    revisionBtn.addEventListener('click', function() {
        revisionBtn.classList.add('btn-active');
        translationBtn.classList.remove('btn-active');
    });
</script>

</body>
</html>
