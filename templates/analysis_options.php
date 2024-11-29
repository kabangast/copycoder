<div class="card bg-dark border border-secondary">
    <div class="card-body">
        <h5 class="card-title mb-4">
            <i class="fas fa-cogs me-2"></i>Analysis Options
        </h5>
        
        <!-- Analysis Focus Dropdown -->
        <div class="mb-4">
            <label class="form-label">Analysis Focus</label>
            <select class="form-select bg-dark text-white border-secondary" id="analysisFocus">
                <option value="code_quality">Code Quality & Best Practices</option>
                <option value="security">Security Analysis</option>
                <option value="performance">Performance Optimization</option>
                <option value="documentation">Documentation Generation</option>
            </select>
        </div>

        <!-- Generate Button -->
        <div class="d-grid">
            <button class="btn btn-primary btn-lg" id="generateBtn" disabled>
                <i class="fas fa-magic me-2"></i>Generate Prompt
                <span class="badge bg-light text-primary ms-2" id="promptCounter">0</span>
            </button>
        </div>

        <!-- Analysis Status -->
        <div class="mt-4" id="analysisStatus" style="display: none;">
            <div class="d-flex align-items-center">
                <div class="spinner-border text-primary me-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span id="analysisStatusText">Analyzing your code...</span>
            </div>
        </div>
    </div>
</div>
