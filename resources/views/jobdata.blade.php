@extends('layouts.dashbordlayout')
@section('content')
<!-- Navbar (Gmail-style) -->
<nav class="navbar">
    <div class="navbar-brand">
        <i class="fas fa-bars" id="menu-toggle"></i> Job Data 
    </div>
    <i class="fas fa-search" style="color: white; float: right; margin-top: -25px;"></i>
</nav>
<!-- Form Container -->
<div class="container">
    <div class="form-header">Job Data Form</div>
    <form>
        <!-- Account and Contact Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="account">Account <span class="text-danger">*</span></label>
                    <select id="account">
                        <option value="demo_client">demo -- client_test</option>
                        <!-- More options can be added -->
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contact">Contact <span class="text-danger">*</span></label>
                    <select id="contact">
                        <option value="client_test">client_test</option>
                        <!-- More options can be added -->
                    </select>
                </div>
            </div>
        </div>

        <!-- Name Field -->
        <div class="form-group">
            <label for="name">Name <span class="text-danger">*</span></label>
            <input type="text" id="name" placeholder="Enter job name">
        </div>

        <!-- Job Type and Workflow Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="jobType">Job Type <span class="text-danger">*</span></label>
                    <select id="jobType">
                        <option value="translation_editing">Translation & Editing</option>
                        <!-- More options can be added -->
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="workflow">Initial Workflow <span class="text-danger">*</span></label>
                    <select id="workflow">
                        <option value="default_te">Default (TE)</option>
                        <!-- More options can be added -->
                    </select>
                </div>
            </div>
        </div>

        <!-- Start Date and Delivery Date Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="startDate">Start Date <span class="text-danger">*</span></label>
                    <input type="datetime-local" id="startDate">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="deliveryDate">Delivery Date <span class="text-danger">*</span></label>
                    <input type="datetime-local" id="deliveryDate">
                </div>
            </div>
        </div>

        <!-- Amount and Unit Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="text" id="amount" placeholder="Enter amount">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="unit">Unit <span class="text-danger">*</span></label>
                    <select id="unit">
                        <option value="word">Word</option>
                        <!-- More options can be added -->
                    </select>
                </div>
            </div>
        </div>

        <!-- Source and Target Language Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="sourceLanguage">Source Language <span class="text-danger">*</span></label>
                    <select id="sourceLanguage">
                        <option value="english_us">English (United States)</option>
                        <!-- More options can be added -->
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="targetLanguage">Target Language <span class="text-danger">*</span></label>
                    <select id="targetLanguage">
                        <option value="arabic_saudi">Arabic (Saudi Arabia)</option>
                        <!-- More options can be added -->
                    </select>
                </div>
            </div>
        </div>

        <!-- Subject Matter Section -->
        <div class="form-group">
            <label for="subjectMatter">Subject Matter <span class="text-danger">*</span></label>
            <select id="subjectMatter">
                <option value="general_sciences">General sciences</option>
                <!-- More options can be added -->
            </select>
        </div>

        <!-- File Uploads Section -->
        <div class="section-title">File Uploads</div>
        <div class="form-group">
            <label for="inFiles">In Files</label>
            <input type="file" id="inFiles" class="file-upload" multiple>
        </div>
        <div class="form-group">
            <label for="instructionFiles">Instruction Files</label>
            <input type="file" id="instructionFiles" class="file-upload" multiple>
        </div>

        <!-- Shared Instructions Section -->
        <div class="form-group">
            <label for="sharedInstructions">Shared Instructions</label>
            <textarea id="sharedInstructions" class="rich-text-editor" placeholder="Enter any special instructions..."></textarea>
        </div>

        <!-- Submit Button -->
        <div class="form-group text-center">
            <button type="button" class="btn-submit">Submit</button>
        </div>
    </form>
</div>
@endsection
