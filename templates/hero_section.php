<div class="text-center py-2">
    <h1 class="display-4 fw-bold mb-4">AI-Powered Code Generation</h1>
    <p class="lead mb-4">Upload your files and generate powerful coding prompts in seconds using advanced AI technology.</p>
    <div class="d-flex justify-content-center gap-3">
        <button class="btn btn-primary btn-lg" id="viewDemoBtn">
            <i class="fas fa-play-circle me-2"></i>View Demo
        </button>
        <button class="btn btn-outline-light btn-lg" id="learnMoreBtn">
            <i class="fas fa-info-circle me-2"></i>Learn More
        </button>
    </div>
</div>

<!-- Video Demo Modal -->
<div class="modal fade" id="demoModal" tabindex="-1" aria-labelledby="demoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="demoModalLabel">
                    <i class="fas fa-play-circle me-2 text-primary"></i>Video Demo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe src="" 
                            id="demoVideo"
                            title="CopyCoder Demo Video" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewDemoBtn = document.getElementById('viewDemoBtn');
    const demoModal = document.getElementById('demoModal');
    const demoVideo = document.getElementById('demoVideo');
    const videoUrl = 'https://www.youtube.com/embed/PE_D-O2PUwQ?start=191&autoplay=1&rel=0&modestbranding=1&hd=1';
    
    // Initialize modal
    const modal = new bootstrap.Modal(demoModal);
    
    // Handle modal events
    demoModal.addEventListener('show.bs.modal', function () {
        demoVideo.src = videoUrl;
    });
    
    demoModal.addEventListener('hide.bs.modal', function () {
        demoVideo.src = ''; // Stop video when modal is closed
    });
    
    // Show modal when demo button is clicked
    viewDemoBtn.addEventListener('click', function() {
        modal.show();
    });
});
</script>
