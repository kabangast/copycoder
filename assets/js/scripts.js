// Make createPromptCard available immediately
window.createPromptCard = function(data) {
    const promptCard = document.createElement('div');
    promptCard.className = 'col-md-4 mb-4 prompt-card-animation';
    promptCard.innerHTML = `
        <div class="card bg-dark border border-secondary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="card-title text-primary mb-0">
                        <i class="fas fa-magic me-2"></i>Generated Prompt
                    </h5>
                    <button class="btn btn-primary btn-copy" 
                            data-bs-toggle="tooltip" 
                            data-bs-placement="left"
                            title="Copy prompt to clipboard">
                        <i class="fas fa-copy me-2"></i>Copy
                    </button>
                </div>
                <div class="prompt-content">
                    <div class="prompt-result">
                        <pre class="mt-2 text-white bg-darker p-2 rounded position-relative mb-2"><code>${data.prompt ? data.prompt.replace(/\n+/g, '\n').trim() : 'No prompt available'}</code></pre>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-sm btn-outline-danger delete-prompt ms-2" 
                                    title="Delete this prompt permanently" 
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="left">
                                <i class="fas fa-trash-alt me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-darker border-top border-secondary">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-light-gray">Generated on ${new Date(data.timestamp || new Date()).toLocaleString()}</small>
                </div>
            </div>
        </div>
    `;

    // Add delete functionality with confirmation
    const deleteBtn = promptCard.querySelector('.delete-prompt');
    deleteBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        // Show confirmation dialog
        if (confirm('Are you sure you want to delete this prompt? This action cannot be undone.')) {
            const savedPrompts = JSON.parse(localStorage.getItem('recentPrompts') || '[]');
            const updatedPrompts = savedPrompts.filter(p => p.timestamp !== data.timestamp);
            localStorage.setItem('recentPrompts', JSON.stringify(updatedPrompts));
            promptCard.classList.add('prompt-card-remove');
            setTimeout(() => {
                promptCard.remove();
                updatePromptCounter();
            }, 300);
        }
    });

    // Add copy functionality with feedback
    const copyBtn = promptCard.querySelector('.btn-copy');
    copyBtn.addEventListener('click', async (e) => {
        e.stopPropagation();
        try {
            await navigator.clipboard.writeText(data.prompt || '');
            copyBtn.classList.add('btn-success');
            copyBtn.innerHTML = '<i class="fas fa-check me-2"></i>Copied!';
            setTimeout(() => {
                copyBtn.classList.remove('btn-success');
                copyBtn.innerHTML = '<i class="fas fa-copy me-2"></i>Copy';
            }, 2000);
        } catch (err) {
            console.error('Failed to copy text:', err);
            copyBtn.classList.add('btn-danger');
            copyBtn.innerHTML = '<i class="fas fa-times me-2"></i>Failed';
            setTimeout(() => {
                copyBtn.classList.remove('btn-danger');
                copyBtn.innerHTML = '<i class="fas fa-copy me-2"></i>Copy';
            }, 2000);
        }
    });

    // Initialize tooltips
    const tooltips = promptCard.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => {
        new bootstrap.Tooltip(tooltip);
    });

    const recentPrompts = document.getElementById('recentPrompts');
    if (recentPrompts) {
        // Insert at the beginning instead of the end
        if (recentPrompts.firstChild) {
            recentPrompts.insertBefore(promptCard, recentPrompts.firstChild);
        } else {
            recentPrompts.appendChild(promptCard);
        }
    }
};

// Helper function to update prompt counter
function updatePromptCounter() {
    // Get all elements with id="promptCounter"
    const counters = document.querySelectorAll('#promptCounter');
    const savedPrompts = JSON.parse(localStorage.getItem('recentPrompts') || '[]');
    
    // Update all counter elements
    counters.forEach(counter => {
        counter.textContent = savedPrompts.length;
    });
}

// Helper function to clear all prompts
function clearAllPrompts() {
    const recentPrompts = document.getElementById('recentPrompts');
    if (confirm("Are you sure you want to clear all prompts?")) {
        localStorage.removeItem('recentPrompts');
        if (recentPrompts) {
            recentPrompts.innerHTML = '';
        }
        updatePromptCounter();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const chooseFileBtn = document.getElementById('chooseFileBtn');
    const uploadStatus = document.getElementById('uploadStatus');
    const uploadStatusText = document.getElementById('uploadStatusText');
    const progressBar = document.querySelector('.progress-bar');
    const generateBtn = document.getElementById('generateBtn');
    const analysisStatus = document.getElementById('analysisStatus');
    const clearPromptsBtn = document.getElementById('clearPrompts');

    // Clear prompts button handler
    if (clearPromptsBtn) {
        clearPromptsBtn.addEventListener('click', clearAllPrompts);
    }

    // Load saved prompts on page load
    const savedPrompts = JSON.parse(localStorage.getItem('recentPrompts') || '[]');
    // Display prompts in reverse order (newest first)
    savedPrompts.reverse().forEach(data => {
        window.createPromptCard(data);
    });
    updatePromptCounter();

    // Track upload state
    let isUploading = false;

    // Handle file selection button click
    if (chooseFileBtn) {
        chooseFileBtn.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent event from bubbling to dropZone
            if (!isUploading) {
                fileInput.click();
            }
        });
    }

    // Drag and Drop Handlers
    if (dropZone) {
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            if (!isUploading) {
                dropZone.classList.add('dragover');
            }
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            if (!isUploading && e.dataTransfer.files.length) {
                handleFileUpload(e.dataTransfer.files[0]);
            }
        });
    }

    if (fileInput) {
        fileInput.addEventListener('change', (e) => {
            if (!isUploading && e.target.files.length) {
                handleFileUpload(e.target.files[0]);
            }
        });

        // Prevent click event from bubbling up to dropZone
        fileInput.addEventListener('click', (e) => {
            if (isUploading) {
                e.preventDefault();
            }
            e.stopPropagation();
        });
    }

    // File Upload Handler
    function handleFileUpload(file) {
        if (isUploading) return;
        
        if (!file.type.startsWith('image/')) {
            alert('Please upload an image file.');
            return;
        }

        isUploading = true;
        uploadStatus.style.display = 'block';
        uploadStatusText.textContent = 'Uploading...';
        progressBar.style.width = '0%';

        const reader = new FileReader();
        reader.readAsDataURL(file);
        
        reader.onload = function() {
            const base64Image = reader.result;
            
            let progress = 0;
            const interval = setInterval(() => {
                progress += 10;
                progressBar.style.width = progress + '%';
                
                if (progress >= 100) {
                    clearInterval(interval);
                    uploadStatusText.textContent = 'Upload complete!';
                    if (generateBtn) generateBtn.disabled = false;
                    isUploading = false;
                }
            }, 200);

            // Store the base64 image for later use
            localStorage.setItem('currentImage', base64Image);
        };

        reader.onerror = function() {
            console.error('Error reading file');
            uploadStatusText.textContent = 'Upload failed. Please try again.';
            isUploading = false;
        };
    }

    // Generate Button Handler
    if (generateBtn) {
        generateBtn.addEventListener('click', async function() {
            const base64Image = localStorage.getItem('currentImage');
            if (!base64Image) {
                alert('Please upload an image first.');
                return;
            }

            // Disable generate button and show analysis status
            generateBtn.disabled = true;
            analysisStatus.style.display = 'block';

            try {
                // Convert base64 to blob
                const base64Response = await fetch(base64Image);
                const blob = await base64Response.blob();

                // Create FormData and append the blob as a file
                const formData = new FormData();
                formData.append('image', blob, 'image.jpg');

                const response = await fetch('gemini-1.5-flash-latest.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                
                // Extract text from Gemini API response format
                const analysisText = result.candidates[0].content.parts[0].text;
                
                // Add prompt to history and update display
                const promptData = {
                    prompt: analysisText,
                    timestamp: new Date().toISOString()
                };
                const savedPrompts = JSON.parse(localStorage.getItem('recentPrompts') || '[]');
                savedPrompts.unshift(promptData);
                if (savedPrompts.length > 10) savedPrompts.pop();
                localStorage.setItem('recentPrompts', JSON.stringify(savedPrompts));
                window.createPromptCard(promptData);
                updatePromptCounter();

                // Clear the stored image
                localStorage.removeItem('currentImage');
                
                // Hide the analysis status after prompt is received
                analysisStatus.style.display = 'none';
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to generate prompt. Please try again.');
                
                // Hide the analysis status on error
                analysisStatus.style.display = 'none';
            } finally {
                // Re-enable generate button after prompt is received or error occurs
                generateBtn.disabled = false;
            }
        });
    }
});
