<div class="card bg-dark border border-secondary">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="fas fa-upload me-2"></i>Upload Your Files
                </h5>
                <div class="upload-zone p-5 text-center border border-2 border-secondary rounded" id="dropZone">
                    <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-primary"></i>
                    <p class="upload-text mb-4">
                        <span class="fs-5 fw-semibold text-white">Drag and drop your files here</span><br>
                        <span class="text-light opacity-75">or click to browse</span>
                    </p>
                    <form id="uploadForm" class="d-none">
                        <input type="file" id="fileInput" accept="image/*">
                    </form>
                    <button class="btn btn-primary" id="chooseFileBtn">
                        <i class="fas fa-folder-open me-2"></i>Choose File
                    </button>
                </div>
                <div class="mt-3" id="uploadStatus" style="display: none;">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                    </div>
                    <small class="text-muted mt-2" id="uploadStatusText"></small>
                </div>
                        <!-- Analysis Status -->
        <div class="mt-4" id="analysisStatus" style="display: none;">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <div class="spinner-border text-primary mb-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span id="analysisStatusText">Analyzing your code...</span>
            </div>
        </div>
                <div class="d-grid mt-4">
                    <button class="btn btn-primary btn-lg" id="generateBtn" disabled="">
                        <i class="fas fa-magic me-2"></i>Generate Prompt
                        <span class="badge bg-light text-primary ms-2" id="promptCounter">10</span>
                    </button>
                </div>
            </div>
        </div>